<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingHomestay;
use App\Models\KamarHomestay;
use App\Models\Warga;
use Faker\Factory as Faker;

class BookingHomestaySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $kamar  = KamarHomestay::pluck('kamar_id')->toArray();
        $warga  = Warga::pluck('warga_id')->toArray();

        if (count($kamar) == 0 || count($warga) == 0) {
            return; // aman jika data belum ada
        }

        for ($i = 1; $i <= 40; $i++) {

            // tanggal checkin & checkout
            $checkin  = $faker->dateTimeBetween('+1 days', '+15 days');
            $checkout = (clone $checkin)->modify('+'.rand(1,4).' days');

            $hargaKamar = rand(120000, 500000); // harga random
            $total = $hargaKamar * rand(1, 4);

            BookingHomestay::create([
                'kamar_id'     => $faker->randomElement($kamar),
                'warga_id'     => $faker->randomElement($warga),
                'checkin'      => $checkin->format('Y-m-d'),
                'checkout'     => $checkout->format('Y-m-d'),
                'total'        => $total,

                // STATUS tanpa "lunas"
                'status'       => $faker->randomElement(['pending', 'batal']),

                // metode bayar valid
                'metode_bayar' => $faker->randomElement(['transfer', 'cash']),
            ]);
        }
    }
}
