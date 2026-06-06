<?php

namespace App\Http\Controllers;

use App\Models\DestinasiWisata;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinasiWisataController extends Controller
{
    // ============================
    // INDEX
    // ============================
    public function index(Request $request)
    {
        $destinasi = DestinasiWisata::with('media')
            ->search($request->search)
            ->filter([
                'rt'        => $request->rt,
                'rw'        => $request->rw,
                'tiket_min' => $request->tiket_min,
                'tiket_max' => $request->tiket_max,
            ])
            ->orderBy('destinasi_id', 'DESC')
            ->paginate(4)
            ->withQueryString();

        return view('destinasi.index', compact('destinasi'));
    }

    // ============================
    // CREATE PAGE
    // ============================
    public function create()
    {
        return view('destinasi.create');
    }

    // ============================
    // STORE
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required',
            'alamat'     => 'required',
            'tiket'      => 'required|numeric',
            'foto.*'     => 'nullable|image|max:2048', // MULTIPLE FOTO
        ]);

        $dest = DestinasiWisata::create($request->except('foto'));

        // Simpan foto (multiple)
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $i => $file) {

                $path = $file->store('destinasi', 'public');

                Media::create([
                    'ref_table'  => 'destinasi_wisata',
                    'ref_id'     => $dest->destinasi_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getClientMimeType(),
                    'caption'    => 'Foto ' . ($i + 1),
                    'sort_order' => $i + 1,
                ]);
            }
        }

        return redirect()->route('destinasi.index')->with('success', "Destinasi berhasil ditambahkan!");
    }

    // ============================
    // AJAX CEK NAMA DUPLIKASI
    // ============================
    public function checkName(Request $request)
    {
        $exists = DestinasiWisata::where('nama', $request->nama)->exists();
        return response()->json(['exists' => $exists]);
    }

    // ============================
    // HAPUS SATU GAMBAR
    // ============================
    public function deleteImage($id)
    {
        $media = Media::findOrFail($id);

        Storage::disk('public')->delete($media->file_url);
        $media->delete();

        return response()->json(['success' => true]);
    }

    // ============================
    // EDIT PAGE
    // ============================
    public function edit($id)
    {
        $dest = DestinasiWisata::with('media')->findOrFail($id);
        return view('destinasi.edit', compact('dest'));
    }

    // ============================
    // UPDATE
    // ============================
    public function update(Request $request, $id)
    {
        $dest = DestinasiWisata::findOrFail($id);

        $request->validate([
            'nama'      => 'required',
            'alamat'    => 'required',
            'tiket'     => 'required|numeric',
            'foto.*'    => 'nullable|image|max:2048',
        ]);

        $dest->update($request->except('foto'));

        // Upload gambar baru (TAMBAH, BUKAN REPLACE)
        if ($request->hasFile('foto')) {

            $lastSort = Media::where('ref_table','destinasi_wisata')
                            ->where('ref_id', $dest->destinasi_id)
                            ->max('sort_order') ?? 0;

            foreach ($request->file('foto') as $i => $file) {
                $path = $file->store('destinasi', 'public');

                Media::create([
                    'ref_table'  => 'destinasi_wisata',
                    'ref_id'     => $dest->destinasi_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getClientMimeType(),
                    'caption'    => 'Foto ' . ($lastSort + $i + 1),
                    'sort_order' => $lastSort + $i + 1,
                ]);
            }
        }

        return redirect()->route('destinasi.index')->with('success', "Destinasi berhasil diperbarui!");
    }

    // ============================
    // DELETE DESTINASI
    // ============================
    public function destroy($id)
    {
        $dest = DestinasiWisata::with('media')->findOrFail($id);

        // Hapus semua media
        foreach ($dest->media as $m) {
            Storage::disk('public')->delete($m->file_url);
            $m->delete();
        }

        $dest->delete();

        return back()->with('success', "Destinasi berhasil dihapus!");
    }
}
