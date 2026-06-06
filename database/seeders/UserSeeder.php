<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Faker Indonesia

        // =====================================================================
        // 1. User Admin Utama
        // =====================================================================
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@pariwisata.com',
            'password' => Hash::make('Admin123'),
            'role'     => 'admin',
        ]);

        // =====================================================================
        // 2. Generate 50 user fake
        // =====================================================================
        for ($i = 1; $i <= 50; $i++) {
            User::create([
                'name'     => $faker->name(),
                'email'    => $faker->unique()->safeEmail(),
                'password' => Hash::make('Admin123'),
                'role'     => $faker->randomElement(['admin', 'petugas']),
            ]);
        }
    }
}
