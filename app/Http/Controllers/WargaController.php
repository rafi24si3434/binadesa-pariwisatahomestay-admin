<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    /* ============================================================
       INDEX (LIST DATA WARGA)
    ============================================================ */
    public function index(Request $request)
    {
        $keyword = $request->search;

        $filters = [
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'pekerjaan'     => $request->pekerjaan,
        ];

        $sortBy    = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'DESC';

        $warga = Warga::search($keyword)
                    ->filter($filters)
                    ->sort($sortBy, $sortOrder)
                    ->paginate(8)
                    ->withQueryString();

        return view('warga.index', [
            'warga'      => $warga,
            'filters'    => $filters,
            'sortBy'     => $sortBy,
            'sortOrder'  => $sortOrder,
        ]);
    }

    /* ============================================================
       CREATE (FORM)
    ============================================================ */
    public function create()
    {
        return view('warga.create');
    }

    /* ============================================================
       STORE (SIMPAN DATA)
    ============================================================ */
    public function store(Request $request)
    {
        $request->validate([
            'no_ktp'        => 'required|unique:warga,no_ktp|digits:16',
            'nama'          => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'email'         => 'nullable|email|unique:warga,email',
        ]);

        Warga::create($request->all());

        return redirect()->route('warga.index')
            ->with('success', 'Data warga berhasil ditambahkan!');
    }

    /* ============================================================
       EDIT (FORM)
    ============================================================ */
    public function edit($id)
    {
        $warga = Warga::findOrFail($id);
        return view('warga.edit', compact('warga'));
    }

    /* ============================================================
       UPDATE (SIMPAN PERUBAHAN)
    ============================================================ */
    public function update(Request $request, $id)
    {
        $warga = Warga::findOrFail($id);

        $request->validate([
            'no_ktp' => "required|digits:16|unique:warga,no_ktp,{$id},warga_id",
            'nama'   => 'required',
            'email'  => "nullable|email|unique:warga,email,{$id},warga_id",
        ]);

        $warga->update($request->all());

        return redirect()->route('warga.index')
            ->with('success', 'Data warga berhasil diperbarui!');
    }

    /* ============================================================
       DELETE
    ============================================================ */
    public function destroy($id)
    {
        Warga::findOrFail($id)->delete();

        return back()->with('success', 'Data berhasil dihapus!');
    }

    /* ============================================================
       AJAX: CEK KTP SUDAH ADA?
    ============================================================ */
    public function checkKTP(Request $request)
    {
        $exists = Warga::where('no_ktp', $request->no_ktp)->exists();
        return response()->json(['exists' => $exists]);
    }

    /* ============================================================
       AJAX: CEK EMAIL SUDAH ADA?
    ============================================================ */
    public function checkEmail(Request $request)
    {
        $exists = Warga::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }
}
