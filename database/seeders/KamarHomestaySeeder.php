<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KamarHomestay;
use App\Models\Homestay;
use Faker\Factory as Faker;

class KamarHomestaySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $fasilitasList = [
            'AC', 'TV', 'WiFi', 'Kamar Mandi Dalam', 'Air Panas',
            'Sarapan', 'Parkir', 'Lemari', 'Meja Kerja', 'Kulkas Mini'
        ];

        // Ambil semua homestay
        $homestays = Homestay::all();

        if ($homestays->count() == 0) {
            echo "⚠️ Tidak ada homestay! Seeder kamar dibatalkan.\n";
            return;
        }

        for ($i = 0; $i < 100; $i++) {

            $homestay = $homestays->random(); // pilih homestay random

            KamarHomestay::create([
                'homestay_id'    => $homestay->homestay_id,
                'nama_kamar'     => 'Kamar ' . $faker->unique()->numerify('###'),
                'kapasitas'      => $faker->numberBetween(1, 6),
                'fasilitas_json' => json_encode(
                    $faker->randomElements($fasilitasList, rand(2, 6))
                ),
                'harga'          => $faker->numberBetween(100000, 800000),
            ]);
        }

        echo "✅ Seeder Kamar Homestay berhasil dibuat 100 data.\n";
    }
}
