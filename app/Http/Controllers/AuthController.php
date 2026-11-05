<?php

namespace App\Http\Controllers;

use App\Models\{User, Profile, Role, UserDevice};
use App\Mail\OtpVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /**
     * Show login full page.
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }

        $redirectTo = $request->query('redirect') 
            ?: session('url.intended') 
            ?: route('home');

        if ($redirectTo && (str_contains($redirectTo, '/login') || str_contains($redirectTo, '/register'))) {
            $redirectTo = route('home');
        }

        if ($redirectTo) {
            session(['url.intended' => $redirectTo]);
        }

        $notice = null;
        if ($request->has('redirect') || session()->has('notice')) {
            $notice = session('notice') ?? 'Silakan login terlebih dahulu untuk melanjutkan.';
        }

        $isRegister = false;

        return view('components.login', compact('redirectTo', 'notice', 'isRegister'));
    }

    /**
     * Show register full page.
     */
    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }

        $redirectTo = $request->query('redirect') 
            ?: session('url.intended') 
            ?: route('home');

        if ($redirectTo && (str_contains($redirectTo, '/login') || str_contains($redirectTo, '/register'))) {
            $redirectTo = route('home');
        }

        if ($redirectTo) {
            session(['url.intended' => $redirectTo]);
        }

        $notice = null;
        $isRegister = true;

        return view('components.login', compact('redirectTo', 'notice', 'isRegister'));
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::validate($credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'login' => 'Email atau password salah. Silakan coba lagi.',
                ]);
        }

        $user = User::where('email', $request->email)->first();

        $userAgent = $request->userAgent() ?: 'Unknown Device';
        $ipAddress = $request->ip();

        // Periksa apakah User-Agent dari request saat ini sudah terdaftar di tabel user_devices
        $deviceExists = UserDevice::where('user_id', $user->id)
            ->where('user_agent', $userAgent)
            ->exists();

        if ($deviceExists) {
            // Perangkat SUDAH terdaftar: langsung Auth::login() dan redirect ke /dashboard
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect('/dashboard')
                ->with('success', 'Berhasil login! Selamat datang, ' . $user->name . '.');
        }

        // Perangkat BELUM terdaftar (Perangkat Baru):
        // 1. Generate kode OTP 6 digit acak
        $otp = sprintf('%06d', random_int(100000, 999999));

        // 2. Simpan kode OTP dan otp_expires_at (berlaku 10 menit) ke tabel users
        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // 3. Kirim email kode OTP ke pengguna menggunakan Mail Laravel
        try {
            Mail::to($user->email)->send(new OtpVerificationMail(
                $user->name, 
                $otp, 
                'Kode Verifikasi Login Perangkat Baru - One Movies'
            ));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email OTP perangkat baru: ' . $e->getMessage());
        }

        // 4. Simpan user_id sementara di session (otp_user_id)
        session([
            'otp_user_id' => $user->id,
            'remember'    => $request->filled('remember'),
        ]);

        // 5. Redirect pengguna ke halaman input OTP (route('otp.view'))
        return redirect()->route('otp.view')
            ->with('status', 'Kami telah mengirimkan kode OTP 6 digit ke email ' . $user->email . '. Silakan verifikasi untuk melanjutkan.');
    }

    /**
     * Handle register request.
     * Manual registration requires @gmail.com and email verification with OTP.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/i'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'name.max'           => 'Nama maksimal 255 karakter.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar. Gunakan email lain.',
            'email.regex'        => 'Email harus menggunakan akun Google (@gmail.com).',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Simpan email dalam huruf kecil
        $email = strtolower($request->email);

        // Buat kode OTP 6 digit acak
        $otp = sprintf('%06d', random_int(100000, 999999));

        // Simpan data sementara di session (nama, email, password yang sudah di-hash, hash OTP, expired 10 menit)
        session([
            'register_pending' => [
                'name'           => $request->name,
                'email'          => $email,
                'password'       => Hash::make($request->password),
                'otp_hash'       => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(10)->timestamp,
                'otp_attempts'   => 0,
                'last_resend_at' => now()->timestamp,
            ],
            'showLoginModal' => true,
            'active_tab'     => 'otp',
        ]);

        // Kirim kode ke email via Mailable dengan try/catch
        try {
            Mail::to($email)->send(new OtpVerificationMail($request->name, $otp));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim OTP saat registrasi: ' . $e->getMessage());

            // Bersihkan session karena gagal kirim
            session()->forget('register_pending');

            return back()
                ->withInput($request->only('name', 'email'))
                ->with('error', 'Gagal mengirim kode, coba lagi nanti.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'register');
        }

        return back()
            ->with('status', 'Kode verifikasi telah dikirim ke ' . $email . '. Silakan periksa kotak masuk atau spam email Anda.')
            ->with('showLoginModal', true)
            ->with('active_tab', 'otp');
    }

    /**
     * Handle OTP verification.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string'],
        ], [
            'otp.required' => 'Kode verifikasi wajib diisi.',
        ]);

        $pending = session('register_pending');

        if (!$pending) {
            return redirect()->route('home')
                ->with('error', 'Sesi pendaftaran telah berakhir. Silakan daftar kembali.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'register');
        }

        // Hitung percobaan kode
        $attempts = ($pending['otp_attempts'] ?? 0) + 1;
        $pending['otp_attempts'] = $attempts;
        session(['register_pending' => $pending]);

        // Batasi percobaan kode maksimal 5 kali
        if ($attempts > 5) {
            session()->forget('register_pending');
            return redirect()->route('home')
                ->with('error', 'Batas percobaan (maksimal 5 kali) telah habis. Silakan daftar ulang.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'register');
        }

        // Cek kedaluwarsa kode (10 menit)
        if (now()->timestamp > $pending['otp_expires_at']) {
            return back()
                ->with('error', 'Kode sudah kedaluwarsa, silakan kirim ulang.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'otp');
        }

        // Cek kecocokan hash kode OTP
        if (!Hash::check($request->otp, $pending['otp_hash'])) {
            if ($attempts >= 5) {
                session()->forget('register_pending');
                return redirect()->route('home')
                    ->with('error', 'Kode salah. Batas percobaan telah habis. Silakan daftar ulang.')
                    ->with('showLoginModal', true)
                    ->with('active_tab', 'register');
            }

            $remaining = 5 - $attempts;
            return back()
                ->with('error', "Kode salah. Sisa percobaan: {$remaining} kali.")
                ->with('showLoginModal', true)
                ->with('active_tab', 'otp');
        }

        // Pastikan email belum didaftarkan di saat yang sama
        if (User::where('email', $pending['email'])->exists()) {
            session()->forget('register_pending');
            return redirect()->route('home')
                ->with('error', 'Email sudah terdaftar, silakan login.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'login');
        }

        // Cari role "user" (bukan admin) dari tabel roles
        $userRole = Role::where('name', 'user')->first();
        $roleId = $userRole ? $userRole->id : 1;

        // Buat profil default user baru
        $profile = Profile::create([
            'alamat' => '-',
            'umur'   => 0,
            'bio'    => '-',
        ]);

        // Buat user di tabel users dengan role user biasa dan tandai email_verified_at = now()
        $user = User::create([
            'name'              => $pending['name'],
            'email'             => $pending['email'],
            'password'          => $pending['password'],
            'role_id'           => $roleId,
            'profile_id'        => $profile->id,
            'email_verified_at' => now(),
        ]);

        // Bersihkan data sementara sesi registrasi
        session()->forget('register_pending');

        // Jangan login otomatis
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        session()->forget('url.intended');

        // Redirect ke beranda dengan pesan dan buka modal login
        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.')
            ->with('showLoginModal', true)
            ->with('active_tab', 'login')
            ->withInput(['email' => $user->email]);
    }

    /**
     * Resend OTP code (limited to 1 time per 60 seconds via RateLimiter).
     */
    public function resendOtp(Request $request)
    {
        $pending = session('register_pending');

        if (!$pending) {
            return redirect()->route('home')
                ->with('error', 'Sesi pendaftaran telah berakhir. Silakan daftar kembali.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'register');
        }

        // RateLimiter: batasi 1 pengiriman per 60 detik per email
        $rateLimitKey = 'otp-resend:' . $pending['email'];
        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()
                ->with('error', "Harap tunggu {$seconds} detik sebelum meminta kode baru.")
                ->with('showLoginModal', true)
                ->with('active_tab', 'otp');
        }

        // Juga cek session-based cooldown sebagai fallback
        $lastResend = $pending['last_resend_at'] ?? 0;
        $elapsed = now()->timestamp - $lastResend;

        if ($elapsed < 60) {
            $remaining = 60 - $elapsed;
            return back()
                ->with('error', "Harap tunggu {$remaining} detik sebelum meminta kode baru.")
                ->with('showLoginModal', true)
                ->with('active_tab', 'otp');
        }

        // Buat kode OTP baru
        $otp = sprintf('%06d', random_int(100000, 999999));
        $pending['otp_hash']       = Hash::make($otp);
        $pending['otp_expires_at'] = now()->addMinutes(10)->timestamp;
        $pending['otp_attempts']   = 0; // Reset percobaan untuk kode baru
        $pending['last_resend_at'] = now()->timestamp;

        session(['register_pending' => $pending]);

        // Hit rate limiter (decay 60 detik)
        RateLimiter::hit($rateLimitKey, 60);

        // Kirim kode ke email via Mailable dengan try/catch
        try {
            Mail::to($pending['email'])->send(new OtpVerificationMail($pending['name'], $otp));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang OTP: ' . $e->getMessage());

            return back()
                ->with('error', 'Gagal mengirim kode, coba lagi nanti.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'otp');
        }

        return back()
            ->with('status', 'Kode verifikasi baru telah dikirim ke ' . $pending['email'] . '.')
            ->with('showLoginModal', true)
            ->with('active_tab', 'otp');
    }

    /**
     * Cancel OTP verification and return to register form.
     */
    public function cancelOtp(Request $request)
    {
        session()->forget('register_pending');

        return back()
            ->with('showLoginModal', true)
            ->with('active_tab', 'register');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Berhasil logout.');
    }

    /**
     * Simple reset password (no email token) – used by the modal form.
     */
    public function simpleReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'))
                ->with('active_tab', 'forgot');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak terdaftar.'])
                ->with('active_tab', 'forgot');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect to home and force the login modal to open with success message
        return redirect()->route('home')
            ->with('success', 'Password berhasil direset, silakan login.')
            ->with('showLoginModal', true)
            ->with('active_tab', 'login');
    }

    /**
     * Show OTP verification form for new device login.
     */
    public function showVerifyOtp(Request $request)
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')
                ->with('warning', 'Sesi verifikasi OTP tidak ditemukan atau telah berakhir. Silakan login kembali.');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget('otp_user_id');
            return redirect()->route('login')
                ->with('warning', 'Pengguna tidak ditemukan. Silakan login kembali.');
        }

        return view('auth.verify-otp', compact('user'));
    }

    /**
     * Handle OTP verification for new device login.
     */
    public function verifyDeviceOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
        ]);

        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi OTP telah berakhir. Silakan login kembali.');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget('otp_user_id');
            return redirect()->route('login')
                ->with('error', 'Pengguna tidak ditemukan. Silakan login kembali.');
        }

        // Validasi input OTP dan pastikan masa berlaku belum expired (now() < otp_expires_at)
        if (!$user->otp_code || !$user->otp_expires_at) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid atau belum dibuat. Silakan login kembali.',
            ]);
        }

        if (now()->gt($user->otp_expires_at)) {
            return back()->withErrors([
                'otp' => 'Kode OTP telah kadaluwarsa (lebih dari 10 menit). Silakan login ulang untuk mendapatkan kode baru.',
            ]);
        }

        if (trim($request->otp) !== (string) $user->otp_code) {
            return back()->withErrors([
                'otp' => 'Kode OTP yang Anda masukkan salah. Silakan periksa kembali email Anda.',
            ]);
        }

        // Jika valid:
        // 1. Kosongkan otp_code dan otp_expires_at pada tabel users
        $user->update([
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);

        // 2. Simpan perangkat baru (user_id, user_agent, dan ip_address) ke tabel user_devices
        UserDevice::create([
            'user_id'    => $user->id,
            'user_agent' => $request->userAgent() ?: 'Unknown Device',
            'ip_address' => $request->ip(),
        ]);

        // 3. Login-kan pengguna secara resmi (Auth::login())
        $remember = session('remember', false);
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // 4. Hapus session temporary (otp_user_id)
        session()->forget(['otp_user_id', 'remember']);

        // 5. Redirect ke /dashboard
        return redirect('/dashboard')
            ->with('success', 'Perangkat berhasil diverifikasi! Selamat datang, ' . $user->name . '.');
    }
}
