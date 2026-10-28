<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordTest extends TestCase
{
    /**
     * Test 1: Home page includes the simple reset password form in the modal.
     */
    public function test_modal_contains_simple_reset_form(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Reset Password');
        $response->assertSee(route('password.simple'));
        $response->assertSee('name="password_confirmation"', false);
        $response->assertSee('Kembali ke Login');
    }

    /**
     * Test 2: Simple password reset succeeds for registered email, hashes password, redirects to home,
     * and allows login with the new password.
     */
    public function test_simple_reset_password_succeeds_and_allows_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->post(route('password.simple'), [
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Password berhasil direset, silakan login.');
        $response->assertSessionHas('showLoginModal', true);
        $response->assertSessionHas('active_tab', 'login');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Test logging in with the new password
        $loginResponse = $this->post(route('login.post'), [
            'email'    => $user->email,
            'password' => 'newpassword123',
        ]);

        $loginResponse->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test 3: Unregistered email returns 'Email tidak terdaftar.' and sets active_tab to forgot.
     */
    public function test_simple_reset_with_unregistered_email_returns_error(): void
    {
        $response = $this->from(route('home'))->post(route('password.simple'), [
            'email'                 => 'unregistered_999@example.com',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            'Email tidak terdaftar.',
            session('errors')->first('email')
        );
        $response->assertSessionHas('active_tab', 'forgot');
    }

    /**
     * Test 4: Validation fails when password is less than 8 characters or confirmation does not match.
     */
    public function test_simple_reset_validates_password_requirements(): void
    {
        $user = User::factory()->create();

        // Too short (< 8)
        $response1 = $this->from(route('home'))->post(route('password.simple'), [
            'email'                 => $user->email,
            'password'              => 'short',
            'password_confirmation' => 'short',
        ]);

        $response1->assertRedirect(route('home'));
        $response1->assertSessionHasErrors(['password']);
        $this->assertEquals('Password minimal 8 karakter.', session('errors')->first('password'));
        $response1->assertSessionHas('active_tab', 'forgot');

        // Mismatched confirmation
        $response2 = $this->from(route('home'))->post(route('password.simple'), [
            'email'                 => $user->email,
            'password'              => 'password123',
            'password_confirmation' => 'mismatch123',
        ]);

        $response2->assertRedirect(route('home'));
        $response2->assertSessionHasErrors(['password']);
        $this->assertEquals('Konfirmasi password tidak cocok.', session('errors')->first('password'));
        $response2->assertSessionHas('active_tab', 'forgot');
    }
}
