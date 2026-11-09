<?php

namespace App\Providers;

use App\Models\Genre;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production') || env('APP_ENV') === 'production' || str_contains(request()->header('host') ?? '', 'railway.app') || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        Paginator::useBootstrap();

        try {
            if (Schema::hasTable('genres')) {
                view()->share('genres', Genre::all());
            }
        } catch (\Throwable $e) {
            // Abaikan jika database belum siap/terhubung (misal saat proses build image / composer install)
        }
    }
}
