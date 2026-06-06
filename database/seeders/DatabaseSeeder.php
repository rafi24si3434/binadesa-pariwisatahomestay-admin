<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $this->call([
            UserSeeder::class,
            WargaSeeder::class,
            DestinasiWisataSeeder::class,
            UlasanWisataSeeder::class,
            HomestaySeeder::class,
            KamarHomestaySeeder::class,
            BookingHomestaySeeder::class    ,

        ]);
    }
}
