<?php

namespace App\Http\Controllers;

use App\Models\{User, Profile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth for registration.
     */
    public function redirectToGoogleRegister(Request $request)
    {
        // Store mode in session so callback knows this is a registration flow
        session(['google_auth_mode' => 'register']);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Redirect to Google OAuth for login.
     */
    public function redirectToGoogleLogin(Request $request)
    {
        session(['google_auth_mode' => 'login']);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google OAuth.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Gagal terhubung dengan Google. Silakan coba lagi.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'login');
        }

        $mode = session()->pull('google_auth_mode', 'register');

        if ($mode === 'login') {
            return $this->handleLoginCallback($googleUser);
        }

        return $this->handleRegisterCallback($googleUser);
    }

    /**
     * Handle Google callback in registration mode.
     * Requirement 2:
     * - Ambil nama dan email dari Google.
     * - Kalau email sudah ada di tabel users, tampilkan pesan "Email sudah terdaftar, silakan login."
     * - Kalau belum ada, buat user baru dengan role user biasa (role_id = 1), simpan google_id, password nullable.
     * Requirement 3:
     * - Jangan langsung login otomatis.
     * - Redirect ke beranda dengan pesan "Registrasi berhasil! Silakan login dengan akun Anda." lalu buka modal login.
     */
    protected function handleRegisterCallback($googleUser)
    {
        $email = $googleUser->getEmail();
        $name  = $googleUser->getName() ?: 'User Google';
        $googleId = $googleUser->getId();

        // Check if email already registered
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            // Link google_id if not linked yet
            if (!$existingUser->google_id) {
                $existingUser->update(['google_id' => $googleId]);
            }

            return redirect()->route('home')
                ->with('error', 'Email sudah terdaftar, silakan login.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'login');
        }

        // Create default profile for the new user
        $profile = Profile::create([
            'alamat' => '-',
            'umur'   => 0,
            'bio'    => '-',
        ]);

        // Create the user with role user (role_id = 1), google_id, and nullable password
        User::create([
            'name'       => $name,
            'email'      => $email,
            'google_id'  => $googleId,
            'role_id'    => 1, // "user" role
            'profile_id' => $profile->id,
            'password'   => null,
        ]);

        // Do not auto-login, ensure clean session
        if (Auth::check()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        session()->forget('url.intended');

        // Redirect to home with success message and open login modal
        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.')
            ->with('showLoginModal', true)
            ->with('active_tab', 'login');
    }

    /**
     * Handle Google callback in login mode.
     */
    protected function handleLoginCallback($googleUser)
    {
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if (!$user) {
            return redirect()->route('home')
                ->with('error', 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.')
                ->with('showLoginModal', true)
                ->with('active_tab', 'register');
        }

        // Update google_id if not set yet
        if (!$user->google_id) {
            $user->update(['google_id' => $googleUser->getId()]);
        }

        Auth::login($user);
        request()->session()->regenerate();

        $target = session()->pull('url.intended');
        if ($target && !str_contains($target, '/login') && !str_contains($target, '/register')) {
            return redirect()->to($target)
                ->with('success', 'Berhasil login! Selamat datang, ' . $user->name . '.');
        }

        return redirect()->route('home')
            ->with('success', 'Berhasil login! Selamat datang, ' . $user->name . '.');
    }
}
