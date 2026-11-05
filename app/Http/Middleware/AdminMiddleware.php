<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return redirect()->route('home')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses area admin.');
        }

        if (! $user->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'Akses ditolak! Halaman ini hanya untuk Administrator.');
        }

        return $next($request);
    }
}
