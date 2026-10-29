<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FilmController,
    AuthController,
    GoogleController,
    KritikController,
};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    FilmController as AdminFilmController,
    CastController as AdminCastController,
    CommentController as AdminCommentController,
    UserController as AdminUserController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Frontend
Route::get('/', [FilmController::class, 'movieHome'])->name('home');
Route::get('/movies', [FilmController::class, 'movies'])->name('movies');
Route::get('/movies/{film}', [FilmController::class, 'show'])->name('movies.show');
Route::get('/genre/{genre}', [FilmController::class, 'moviesByGenre'])->name('genre');
Route::get('/movies/genre/{genre}', [FilmController::class, 'moviesByGenre']);
Route::get('/faq', [FilmController::class, 'faq'])->name('faq');
Route::post('/subscribe', [FilmController::class, 'subscribe'])->middleware('auth')->name('subscribe');

// Comments (Protected inside controller / middleware)
Route::post('/movies/{film}/comments', [KritikController::class, 'store'])->name('comments.store');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/register/verify-otp', [AuthController::class, 'verifyOtp'])->name('register.verify_otp');
Route::post('/register/resend-otp', [AuthController::class, 'resendOtp'])->name('register.resend_otp');
Route::post('/register/cancel-otp', [AuthController::class, 'cancelOtp'])->name('register.cancel_otp');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Simple Password Reset (no email token)
Route::post('/simple-reset', [AuthController::class, 'simpleReset'])->name('password.simple');

// Google OAuth Routes
Route::get('/auth/google/register', [GoogleController::class, 'redirectToGoogleRegister'])->name('google.register');
Route::get('/auth/google/login', [GoogleController::class, 'redirectToGoogleLogin'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Admin Panel (Protected by Auth & Admin Middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Film Management CRUD
    Route::resource('films', AdminFilmController::class);

    // Cast Management CRUD
    Route::resource('casts', AdminCastController::class);

    // Comment Moderation
    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::match(['put', 'patch'], '/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});
