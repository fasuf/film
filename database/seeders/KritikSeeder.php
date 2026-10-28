<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Kritik, User, Film};
use Faker\Factory as Faker;

class KritikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        $filmIds = Film::pluck('id')->toArray();

        if (empty($userIds) || empty($filmIds)) {
            return;
        }

        $faker = Faker::create('id_ID');

        for ($loop = 1; $loop <= 300; $loop++) {
            Kritik::create([
                'comment' => $faker->paragraph(2),
                'rating'  => $faker->numberBetween(5, 10),
                'film_id' => $faker->randomElement($filmIds),
                'user_id' => $faker->randomElement($userIds),
            ]);
        }
    }
}
