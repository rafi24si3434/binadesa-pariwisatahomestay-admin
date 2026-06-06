<?php
namespace App\Http\Controllers;

use App\Models\Homestay;
use App\Models\KamarHomestay;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarHomestayController extends Controller
{
    // ============================================
    // LIST VIEW (GROUP HOMESTAY)
    // ============================================
    public function index(Request $request)
    {
        $filters = [
            'homestay_id' => $request->homestay_id,
            'harga_min'   => $request->harga_min,
            'harga_max'   => $request->harga_max,
        ];

        // --------------------------
        // DATA UNTUK GROUPING
        // --------------------------
        $kamarGroup = KamarHomestay::with(['media', 'homestay'])
            ->search($request->search)
            ->filter($filters)
            ->orderBy('kamar_id', 'DESC')
            ->get(); // ← WAJIB GET() untuk grouping

        // --------------------------
        // DATA HOMESTAY
        // --------------------------
        $homestay = Homestay::orderBy('nama')->get();

        // --------------------------
        // PAGINATION GLOBAL
        // --------------------------
        $paginate = KamarHomestay::orderBy('kamar_id', 'DESC')
            ->paginate(6)
            ->withQueryString();

        return view('kamar.index', [
            'kamarGroup' => $kamarGroup, // untuk grouping slider
            'homestay'   => $homestay,   // daftar homestay
            'filters'    => $filters,
            'paginate'   => $paginate, // pagination global
        ]);
    }

    // ============================================
    // CREATE FORM
    // ============================================
    public function create()
    {
        $homestay = Homestay::orderBy('nama')->get();
        return view('kamar.create', compact('homestay'));
    }

    // ============================================
    // STORE
    // ============================================
    public function store(Request $request)
    {
        $request->validate([
            'homestay_id' => 'required|exists:homestay,homestay_id',
            'nama_kamar'  => 'required',
            'kapasitas'   => 'required|integer|min:1',
            'harga'       => 'required|numeric',
            'foto.*'      => 'nullable|image|max:2056',
        ]);

        $kamar = KamarHomestay::create([
            'homestay_id'    => $request->homestay_id,
            'nama_kamar'     => $request->nama_kamar,
            'kapasitas'      => $request->kapasitas,
            'fasilitas_json' => json_encode($request->fasilitas ?? []),
            'harga'          => $request->harga,
        ]);

        // Upload multiple images
        if ($request->hasFile('foto')) {
            foreach ($request->foto as $i => $file) {
                $path = $file->store('kamar_homestay', 'public');

                Media::create([
                    'ref_table'  => 'kamar_homestay',
                    'ref_id'     => $kamar->kamar_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getClientMimeType(),
                    'caption'    => 'Foto Kamar',
                    'sort_order' => $i + 1,
                ]);
            }
        }

        return redirect()->route('kamar.index')
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    // ============================================
    // EDIT FORM
    // ============================================
    public function edit($id)
    {
        $kamar    = KamarHomestay::with('media')->findOrFail($id);
        $homestay = Homestay::orderBy('nama')->get();

        return view('kamar.edit', compact('kamar', 'homestay'));
    }

    // ============================================
    // UPDATE
    // ============================================
    public function update(Request $request, $id)
    {
        $kamar = KamarHomestay::findOrFail($id);

        $request->validate([
            'nama_kamar' => 'required',
            'kapasitas'  => 'required|integer|min:1',
            'harga'      => 'required|numeric',
            'foto.*'     => 'nullable|image|max:2056',
        ]);

        $kamar->update([
            'homestay_id'    => $request->homestay_id,
            'nama_kamar'     => $request->nama_kamar,
            'kapasitas'      => $request->kapasitas,
            'fasilitas_json' => json_encode($request->fasilitas ?? []),
            'harga'          => $request->harga,
        ]);

        // Tambah foto baru (tanpa menghapus lama)
        if ($request->hasFile('foto')) {
            foreach ($request->foto as $file) {

                $path = $file->store('kamar_homestay', 'public');

                Media::create([
                    'ref_table'  => 'kamar_homestay',
                    'ref_id'     => $kamar->kamar_id,
                    'file_url'   => $path,
                    'mime_type'  => $file->getClientMimeType(),
                    'caption'    => 'Foto Kamar Baru',
                    'sort_order' => Media::where('ref_table', 'kamar_homestay')
                        ->where('ref_id', $kamar->kamar_id)
                        ->count() + 1,
                ]);
            }
        }

        return redirect()->route('kamar.index')
            ->with('success', 'Kamar berhasil diperbarui!');
    }

    // ============================================
    // DELETE FOTO
    // ============================================
    public function deleteImage($id)
    {
        $media = Media::findOrFail($id);
        Storage::disk('public')->delete($media->file_url);
        $media->delete();

        return response()->json(['success' => true]);
    }

    // ============================================
    // DELETE KAMAR
    // ============================================
    public function destroy($id)
    {
        $kamar = KamarHomestay::with('media')->findOrFail($id);

        foreach ($kamar->media as $m) {
            Storage::disk('public')->delete($m->file_url);
            $m->delete();
        }

        $kamar->delete();

        return back()->with('success', 'Kamar berhasil dihapus!');
    }
}
