<?php
namespace App\Http\Controllers;

use App\Models\BookingHomestay;
use App\Models\DestinasiWisata;
use App\Models\Homestay;
use App\Models\UlasanWisata;
use App\Models\Warga;
// ⬅️ TAMBAHAN PENTING

class DashboardController extends Controller
{
    public function index()
    {
        // ===============================
        //  STATISTIK UTAMA
        // ===============================
        $totalHomestay  = Homestay::count();
        $totalDestinasi = DestinasiWisata::count();
        $totalBooking   = BookingHomestay::count();
        $totalUlasan    = UlasanWisata::count();

        // ===============================
        //  CHART – DUMMY (BISA DIGANTI REAL)
        // ===============================
        $chartKunjungan = [50, 70, 65, 90, 120, 160, 140];

        $chartHunian = [
            'labels' => Homestay::pluck('nama'),
            'values' => Homestay::pluck('harga_per_malam'),
        ];

        // ===============================
        //  HOMESTAY TERBARU
        // ===============================
        $homestayTerbaru = Homestay::orderBy('homestay_id', 'DESC')
            ->limit(15)
            ->get();

        // ===============================
        //  ULASAN TERBARU
        // ===============================
        $ulasanTerbaru = UlasanWisata::with(['warga', 'destinasi'])
            ->orderBy('ulasan_id', 'DESC')
            ->limit(5)
            ->get();

        // ===============================
        //  WARGA TERBARU  ⬅️ BUAT NGISI $wargaTerbaru
        // ===============================
        $wargaTerbaru = Warga::orderBy('warga_id', 'DESC')
            ->limit(5)
            ->get();

        // ===============================
        //  EVENT WISATA (DUMMY)
        // ===============================
        $eventWisata = [
            ['nama' => 'Festival Kuliner Desa', 'tanggal' => '12 Februari'],
            ['nama' => 'Gerak Jalan Sehat', 'tanggal' => '20 Februari'],
            ['nama' => 'Lomba Foto Wisata', 'tanggal' => '5 Maret'],
        ];

        // ===============================
        //  KIRIM DATA KE VIEW
        // ===============================
        return view('dashboard', compact(
            'totalHomestay',
            'totalDestinasi',
            'totalBooking',
            'totalUlasan',
            'chartKunjungan',
            'chartHunian',
            'homestayTerbaru',
            'eventWisata',
            'ulasanTerbaru',
            'wargaTerbaru' // ⬅️ JANGAN LUPA DIKIRIM
        ));
    }
}
