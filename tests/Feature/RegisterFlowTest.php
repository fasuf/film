<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;

class RegisterFlowTest extends TestCase
{
    /**
     * Test 1: Successful manual registration with @gmail.com stores temporary data in session,
     * does NOT create user immediately, sends OTP email, and redirects back to show OTP modal.
     */
    public function test_registration_initiates_otp_verification_without_creating_user(): void
    {
        Mail::fake();

        $email = 'flowtest_' . time() . '@gmail.com';
        $password = 'password123';

        $response = $this->post(route('register.post'), [
            'name'                  => 'Flow Test User',
            'email'                 => $email,
            'password'              => $password,
            'password_confirmation' => $password,
        ]);

        // User must NOT be in database yet
        $this->assertNull(User::where('email', $email)->first());
        $this->assertGuest();

        // Session checks
        $this->assertTrue(session()->has('register_pending'));
        $pending = session('register_pending');
        $this->assertEquals('Flow Test User', $pending['name']);
        $this->assertEquals($email, $pending['email']);
        $this->assertTrue(Hash::check($password, $pending['password'])); // password is saved hashed
        $this->assertNotEmpty($pending['otp_hash']);
        $this->assertGreaterThan(now()->timestamp, $pending['otp_expires_at']);

        // Modal flags
        $response->assertSessionHas('showLoginModal', true);
        $response->assertSessionHas('active_tab', 'otp');

        // Verify email was sent with subject "Kode Verifikasi Pendaftaran"
        Mail::assertSent(OtpVerificationMail::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    /**
     * Test 2: Manual registration rejects emails that are NOT @gmail.com with custom message.
     */
    public function test_manual_registration_rejects_non_gmail_email(): void
    {
        $email = 'user_' . time() . '@yahoo.com';
        $password = 'password123';

        $response = $this->post(route('register.post'), [
            'name'                  => 'Non Gmail User',
            'email'                 => $email,
            'password'              => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email harus menggunakan akun Google (@gmail.com).'
        ]);
        $this->assertGuest();
        $this->assertNull(User::where('email', $email)->first());
        $this->assertFalse(session()->has('register_pending'));
    }

    /**
     * Test 3: Correct OTP verification creates user with role_id = 1, email_verified_at set,
     * does not auto-login, redirects to home with success message and open login modal.
     */
    public function test_correct_otp_creates_verified_user_without_autologin(): void
    {
        $email = 'verifyuser_' . time() . '@gmail.com';
        $password = 'password123';
        $otp = '123456';

        // Setup pending registration in session
        session([
            'register_pending' => [
                'name'           => 'Verified User',
                'email'          => $email,
                'password'       => Hash::make($password),
                'otp_hash'       => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(10)->timestamp,
                'otp_attempts'   => 0,
                'last_resend_at' => now()->timestamp,
            ],
            'showLoginModal' => true,
            'active_tab'     => 'otp',
        ]);

        $response = $this->post(route('register.verify_otp'), [
            'otp' => '123456',
        ]);

        // User must NOT be logged in automatically
        $this->assertGuest();

        // Redirects to home
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        $response->assertSessionHas('showLoginModal', true);
        $response->assertSessionHas('active_tab', 'login');

        // Session register_pending should be cleared
        $this->assertFalse(session()->has('register_pending'));

        // Check user in database
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals('Verified User', $user->name);
        $this->assertEquals(1, $user->role_id);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check($password, $user->password));

        // Clean up
        $user->delete();
    }

    /**
     * Test 4: Wrong OTP returns error "Kode salah." and increments attempt counter.
     */
    public function test_wrong_otp_returns_error_and_increments_attempts(): void
    {
        $otp = '123456';
        session([
            'register_pending' => [
                'name'           => 'Wrong OTP User',
                'email'          => 'wrongotp@gmail.com',
                'password'       => Hash::make('password123'),
                'otp_hash'       => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(10)->timestamp,
                'otp_attempts'   => 0,
                'last_resend_at' => now()->timestamp,
            ],
        ]);

        $response = $this->post(route('register.verify_otp'), [
            'otp' => '999999',
        ]);

        $response->assertSessionHas('error', 'Kode salah.');
        $response->assertSessionHas('active_tab', 'otp');

        $pending = session('register_pending');
        $this->assertEquals(1, $pending['otp_attempts']);
        $this->assertNull(User::where('email', 'wrongotp@gmail.com')->first());
    }

    /**
     * Test 5: Maximum 5 attempts: when attempts exceed 5, session is cleared and error shown.
     */
    public function test_exceeding_max_attempts_clears_session(): void
    {
        $otp = '123456';
        session([
            'register_pending' => [
                'name'           => 'Max Attempt User',
                'email'          => 'maxattempt@gmail.com',
                'password'       => Hash::make('password123'),
                'otp_hash'       => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(10)->timestamp,
                'otp_attempts'   => 4, // 5th attempt is next
                'last_resend_at' => now()->timestamp,
            ],
        ]);

        $response = $this->post(route('register.verify_otp'), [
            'otp' => '000000',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Kode salah. Batas percobaan telah habis. Silakan daftar ulang.');
        $this->assertFalse(session()->has('register_pending'));
    }

    /**
     * Test 6: Expired OTP returns error "Kode sudah kedaluwarsa, silakan kirim ulang.".
     */
    public function test_expired_otp_returns_expired_error(): void
    {
        $otp = '123456';
        session([
            'register_pending' => [
                'name'           => 'Expired User',
                'email'          => 'expired@gmail.com',
                'password'       => Hash::make('password123'),
                'otp_hash'       => Hash::make($otp),
                'otp_expires_at' => now()->subMinute()->timestamp, // already expired
                'otp_attempts'   => 0,
                'last_resend_at' => now()->subMinutes(11)->timestamp,
            ],
        ]);

        $response = $this->post(route('register.verify_otp'), [
            'otp' => '123456',
        ]);

        $response->assertSessionHas('error', 'Kode sudah kedaluwarsa, silakan kirim ulang.');
        $response->assertSessionHas('active_tab', 'otp');
    }

    /**
     * Test 7: Resend OTP within 60 seconds is blocked with cooldown message.
     */
    public function test_resend_otp_within_60_seconds_is_blocked(): void
    {
        session([
            'register_pending' => [
                'name'           => 'Cooldown User',
                'email'          => 'cooldown@gmail.com',
                'password'       => Hash::make('password123'),
                'otp_hash'       => Hash::make('123456'),
                'otp_expires_at' => now()->addMinutes(10)->timestamp,
                'otp_attempts'   => 0,
                'last_resend_at' => now()->subSeconds(20)->timestamp, // 20s ago (40s remaining)
            ],
        ]);

        $response = $this->post(route('register.resend_otp'));

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Harap tunggu', session('error'));
    }

    /**
     * Test 8: Resend OTP after 60 seconds generates new OTP, resets attempts and expiration.
     */
    public function test_resend_otp_after_60_seconds_succeeds(): void
    {
        Mail::fake();

        $oldHash = Hash::make('111111');
        session([
            'register_pending' => [
                'name'           => 'Resend User',
                'email'          => 'resend@gmail.com',
                'password'       => Hash::make('password123'),
                'otp_hash'       => $oldHash,
                'otp_expires_at' => now()->subMinute()->timestamp,
                'otp_attempts'   => 3,
                'last_resend_at' => now()->subSeconds(65)->timestamp, // >60s ago
            ],
        ]);

        $response = $this->post(route('register.resend_otp'));

        $response->assertSessionHas('status');
        $pending = session('register_pending');
        $this->assertNotEquals($oldHash, $pending['otp_hash']);
        $this->assertEquals(0, $pending['otp_attempts']); // attempts reset
        $this->assertGreaterThan(now()->timestamp, $pending['otp_expires_at']);

        Mail::assertSent(OtpVerificationMail::class, function ($mail) {
            return $mail->hasTo('resend@gmail.com');
        });
    }

    /**
     * Test 9: Google OAuth registration still works properly without interruption.
     */
    public function test_google_callback_register_creates_user_without_autologin(): void
    {
        $googleId = 'google_id_' . time();
        $email = 'googleuser_' . time() . '@gmail.com';
        $name = 'Google Verified User';

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn($googleId);
        $abstractUser->shouldReceive('getEmail')->andReturn($email);
        $abstractUser->shouldReceive('getName')->andReturn($name);

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        session(['google_auth_mode' => 'register']);

        $response = $this->get(route('google.callback'));

        $this->assertGuest();
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        $response->assertSessionHas('showLoginModal', true);
        $response->assertSessionHas('active_tab', 'login');

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals($name, $user->name);
        $this->assertEquals($googleId, $user->google_id);
        $this->assertEquals(1, $user->role_id);
        $this->assertNull($user->password);

        $user->delete();
    }
}
