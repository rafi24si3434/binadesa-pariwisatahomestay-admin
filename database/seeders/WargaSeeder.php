<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warga;
use Faker\Factory as Faker;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        $pekerjaan = [
            'Petani', 'Guru', 'Karyawan', 'Wiraswasta',
            'Nelayan', 'Pedagang', 'Mahasiswa', 'Ibu Rumah Tangga'
        ];

        for ($i = 0; $i < 100; $i++) {

            Warga::create([
                'no_ktp'        => $faker->nik(),
                'nama'          => $faker->name(),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'agama'         => $faker->randomElement($agama),
                'pekerjaan'     => $faker->randomElement($pekerjaan),
                'telp'          => $faker->phoneNumber(),
                'email'         => $faker->unique()->safeEmail(),
            ]);
        }
    }
}
