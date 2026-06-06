<?php
namespace Database\Seeders;

use App\Models\Homestay;
use App\Models\Warga;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class HomestaySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua warga untuk pemilik
        $pemilikList = Warga::pluck('warga_id')->toArray();

        if (empty($pemilikList)) {
            echo "❗ Data warga kosong! Jalankan WargaSeeder dulu.\n";
            return;
        }

        $fasilitasList = ['WiFi', 'AC', 'Sarapan', 'Parkir', 'TV', 'Kamar Mandi Dalam'];

        for ($i = 0; $i < 50; $i++) {

            Homestay::create([
                'pemilik_warga_id' => $faker->randomElement($pemilikList),

                // Nama homestay random, elegan
                'nama'             => 'Homestay ' . $faker->city(),

                // Alamat Indonesia realistik
                'alamat'           => $faker->streetAddress() . ', ' . $faker->city(),

                'rt'               => rand(1, 20),
                'rw'               => rand(1, 20),

                'fasilitas_json'   => json_encode(
                    $faker->randomElements($fasilitasList, rand(2, 5))
                ),

                'harga_per_malam'  => $faker->numberBetween(75000, 400000),

                'status'           => $faker->randomElement(['tersedia', 'penuh', 'tutup']),
            ]);
        }

        echo "✔ Seeder Homestay berhasil dijalankan!\n";
    }
}
