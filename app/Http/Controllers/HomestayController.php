<?php
namespace App\Http\Controllers;

use App\Models\Homestay;
use App\Models\Media;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomestayController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        $keyword = $request->search;
        $filters = [
            'rt'               => $request->rt,
            'rw'               => $request->rw,
            'status'           => $request->status,
            'pemilik_warga_id' => $request->pemilik, // tambahkan jika filtering pemilik diperlukan
        ];

        // Ambil data homestay
        $homestay = Homestay::with(['media', 'pemilik'])
            ->search($keyword)
            ->filter($filters)
            ->orderBy('homestay_id', 'DESC')
            ->paginate(3)
            ->withQueryString();

        // Ambil daftar pemilik (warga)
        $pemilik =Warga::orderBy('nama')->get();

        return view('homestay.index', [
            'homestay' => $homestay,
            'filters'  => $filters,
            'pemilik'  => $pemilik, // ⬅️ WAJIB supaya view tidak error
        ]);
    }

    // CREATE FORM
    public function create()
    {
        $warga = Warga::orderBy('nama', 'ASC')->get();
        return view('homestay.create', compact('warga'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'pemilik_warga_id' => 'required|exists:warga,warga_id',
            'nama'             => 'required',
            'alamat'           => 'required',
            'harga_per_malam'  => 'required|numeric',
            'foto.*'           => 'nullable|image|max:2048',
        ]);

        $hs = Homestay::create([
            'pemilik_warga_id' => $request->pemilik_warga_id,
            'nama'             => $request->nama,
            'alamat'           => $request->alamat,
            'rt'               => $request->rt,
            'rw'               => $request->rw,
            'status'           => $request->status ?? 'tersedia',
            'fasilitas_json'   => json_encode($request->fasilitas ?? []),
            'harga_per_malam'  => $request->harga_per_malam,
        ]);

        // Upload multiple foto
        if ($request->hasFile('foto')) {
            foreach ($request->foto as $i => $file) {
                $path = $file->store('homestay', 'public');

                Media::create([
                    'ref_table'  => 'homestay',
                    'ref_id'     => $hs->homestay_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getClientMimeType(),
                    'caption'    => 'Homestay Foto',
                    'sort_order' => $i + 1,
                ]);
            }
        }

        return redirect()->route('homestay.index')
            ->with('success', 'Homestay berhasil ditambahkan!');
    }

    // EDIT FORM
    public function edit($id)
    {
        $hs    = Homestay::with('media')->findOrFail($id);
        $warga = Warga::orderBy('nama', 'ASC')->get();
        return view('homestay.edit', compact('hs', 'warga'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $hs = Homestay::findOrFail($id);

        $request->validate([
            'nama'            => 'required',
            'alamat'          => 'required',
            'harga_per_malam' => 'required|numeric',
            'foto.*'          => 'nullable|image|max:2048',
        ]);

        $hs->update([
            'pemilik_warga_id' => $request->pemilik_warga_id,
            'nama'             => $request->nama,
            'alamat'           => $request->alamat,
            'rt'               => $request->rt,
            'rw'               => $request->rw,
            'status'           => $request->status,
            'fasilitas_json'   => json_encode($request->fasilitas ?? []),
            'harga_per_malam'  => $request->harga_per_malam,
        ]);

        // Tambah foto baru (tidak hapus lama)
        if ($request->hasFile('foto')) {
            foreach ($request->foto as $i => $file) {
                $path = $file->store('homestay', 'public');

                Media::create([
                    'ref_table'  => 'homestay',
                    'ref_id'     => $hs->homestay_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getClientMimeType(),
                    'caption'    => 'Homestay Foto Baru',
                    'sort_order' => Media::where('ref_table', 'homestay')
                        ->where('ref_id', $hs->homestay_id)
                        ->count() + 1,
                ]);
            }
        }

        return redirect()->route('homestay.index')
            ->with('success', 'Homestay berhasil diperbarui!');
    }

    // DELETE FOTO
    public function deleteImage($id)
    {
        $media = Media::findOrFail($id);

        Storage::disk('public')->delete($media->file_url);

        $media->delete();

        return response()->json(['success' => true]);
    }

    // DELETE HOMESTAY
    public function destroy($id)
    {
        $hs = Homestay::findOrFail($id);

        // Hapus foto
        foreach ($hs->media as $m) {
            Storage::disk('public')->delete($m->file_url);
            $m->delete();
        }

        $hs->delete();

        return back()->with('success', 'Homestay berhasil dihapus!');
    }
}
