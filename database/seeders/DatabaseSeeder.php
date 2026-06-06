<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $this->call([
           CreateUserSeeder::class,
            CreateWargaSeeder::class,
            CreateKeluargaKKSeeder::class,
            CreateAnggotaKeluargaSeeder::class,
            CreatePeristiwaKelahiranSeeder::class,

        ]);
    }
}
