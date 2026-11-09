<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Film;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (Role::count() === 0) {
            $this->call(RoleSeeder::class);
        }

        if (Genre::count() === 0) {
            $this->call(GenreSeeder::class);
        }

        if (Film::count() === 0) {
            $this->call([
                CastSeeder::class,
                FilmSeeder::class,
                PeranSeeder::class,
                ProfileSeeder::class,
                UserSeeder::class,
                AdminSeeder::class,
                KritikSeeder::class,
            ]);
        }
    }
}
