<?php

namespace App\Http\Controllers;

use App\Models\UlasanWisata;
use App\Models\DestinasiWisata;
use App\Models\Warga;
use Illuminate\Http\Request;

class UlasanWisataController extends Controller
{
    // ==============================
    // INDEX
    // ==============================
    public function index(Request $request)
    {
        $filters = [
            'rating'       => $request->rating,
            'destinasi_id' => $request->destinasi_id,
        ];

        $ulasan = UlasanWisata::with(['destinasi', 'warga'])
            ->search($request->search)
            ->filter($filters)
            ->orderBy('ulasan_id', 'DESC')
            ->paginate(12)
            ->withQueryString();

        return view('ulasan.index', [
            'ulasan'    => $ulasan,
            'filters'   => $filters,
            'destinasi' => DestinasiWisata::orderBy('nama')->get(),
        ]);
    }

    // ==============================
    // CREATE
    // ==============================
    public function create()
    {
        return view('ulasan.create', [
            'destinasi' => DestinasiWisata::orderBy('nama')->get(),
            'warga'     => Warga::orderBy('nama')->get(),
        ]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'destinasi_id' => 'required|exists:destinasi_wisata,destinasi_id',
            'warga_id'     => 'required|exists:warga,warga_id',
            'rating'       => 'required|integer|min:1|max:5',
            'komentar'     => 'nullable|string',
        ]);

        UlasanWisata::create([
            'destinasi_id' => $request->destinasi_id,
            'warga_id'     => $request->warga_id,
            'rating'       => $request->rating,
            'komentar'     => $request->komentar,
            'waktu'        => now(),
        ]);

        return redirect()->route('ulasan.index')
            ->with('success', 'Ulasan berhasil ditambahkan!');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        return view('ulasan.edit', [
            'ulasan'    => UlasanWisata::findOrFail($id),
            'destinasi' => DestinasiWisata::orderBy('nama')->get(),
            'warga'     => Warga::orderBy('nama')->get(),
        ]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update(Request $request, $id)
    {
        $ulasan = UlasanWisata::findOrFail($id);

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        $ulasan->update([
            'rating'   => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('ulasan.index')
            ->with('success', 'Ulasan berhasil diperbarui!');
    }

    // ==============================
    // DELETE
    // ==============================
    public function destroy($id)
    {
        UlasanWisata::findOrFail($id)->delete();

        return back()->with('success', 'Ulasan berhasil dihapus!');
    }
}
