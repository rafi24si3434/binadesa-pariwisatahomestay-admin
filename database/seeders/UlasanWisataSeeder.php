<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UlasanWisata;
use App\Models\DestinasiWisata;
use App\Models\Warga;
use Faker\Factory as Faker;

class UlasanWisataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $destinasi = DestinasiWisata::pluck('destinasi_id')->toArray();
        $warga     = Warga::pluck('warga_id')->toArray();

        // Jika tidak ada data, hentikan seeder
        if (count($destinasi) == 0 || count($warga) == 0) {
            return;
        }

        for ($i = 1; $i <= 80; $i++) {

            UlasanWisata::create([
                'destinasi_id' => $faker->randomElement($destinasi),
                'warga_id'     => $faker->randomElement($warga),

                // Rating 1–5
                'rating'       => $faker->numberBetween(1, 5),

                // Komentar Indonesia yang natural
                'komentar'     => $faker->randomElement([
                    'Tempatnya sangat indah dan bersih!',
                    'Pelayanan ramah dan suasananya nyaman.',
                    'Harga tiket cukup terjangkau.',
                    'Pemandangan bagus tapi fasilitas kurang lengkap.',
                    'Cukup memuaskan, akan datang lagi.',
                    'Lokasi mudah dijangkau dan tempatnya luas.',
                    'Kurang perawatan, tapi masih oke untuk dikunjungi.',
                    'Sangat direkomendasikan untuk liburan keluarga!',
                ]),

                // Waktu ulasan acak 30 hari terakhir
                'waktu'        => $faker->dateTimeBetween('-30 days', 'now'),
            ]);
        }
    }
}
