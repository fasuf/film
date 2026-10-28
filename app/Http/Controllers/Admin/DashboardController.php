<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Film, Cast, User, Kritik, Genre};

class DashboardController extends Controller
{
    /**
     * Show admin dashboard overview with stats and recent activity.
     */
    public function index()
    {
        $stats = [
            'total_films'    => Film::count(),
            'total_casts'    => Cast::count(),
            'total_genres'   => Genre::count(),
            'total_users'    => User::count(),
            'total_kritiks'  => Kritik::count(),
        ];

        $recentFilms = Film::with('genre')->latest()->take(5)->get();
        $recentComments = Kritik::with(['user', 'film'])->latest()->take(5)->get();
        $recentUsers = User::with('role')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentFilms', 'recentComments', 'recentUsers'));
    }
}
