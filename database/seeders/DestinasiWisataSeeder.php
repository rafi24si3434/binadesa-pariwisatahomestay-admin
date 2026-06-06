<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DestinasiWisata;
use Faker\Factory as Faker;

class DestinasiWisataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $namaWisata = [
            'Pantai Indah', 'Air Terjun Pelangi', 'Hutan Pinus Asri', 'Bukit Bintang',
            'Danau Biru', 'Taman Anggrek Nusantara', 'Goa Kristal', 'Gunung Pelangi',
            'Kampung Adat Nusantara', 'Taman Safari Mini', 'Agrowisata Jambu Kristal',
            'Kebun Teh Harmoni', 'Desa Wisata Alam Asri', 'Taman Bunga Matahari',
            'Bukit Senja', 'Pantai Pasir Putih', 'Wisata Mangrove Hijau',
            'Air Terjun Seribu Tangga', 'Kawasan Kuliner Tradisional',
            'Taman Wisata Edukasi', 'Kampung Batik', 'Curug Sumber Rejeki',
            'Hutan Wisata Cemara', 'Goa Batu Hijau'
        ];

        for ($i = 1; $i <= 50; $i++) {

            DestinasiWisata::create([
                'nama'      => $faker->randomElement($namaWisata) . " " . $faker->citySuffix(),
                'deskripsi' => $faker->realTextBetween(80, 200),
                'alamat'    => $faker->streetAddress() . ", " . $faker->city() . ", " . $faker->state(),
                'rt'        => $faker->numberBetween(1, 20),
                'rw'        => $faker->numberBetween(1, 20),
                'jam_buka'  => $faker->randomElement([
                    '07.00–17.00 WIB',
                    '08.00–18.00 WIB',
                    '06.30–17.30 WIB',
                    '24 Jam'
                ]),
                'tiket'     => $faker->numberBetween(5000, 50000),
                'kontak'    => $faker->phoneNumber(),
            ]);
        }
    }
}
