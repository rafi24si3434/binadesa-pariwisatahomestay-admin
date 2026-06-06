<?php
namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // =====================================================
    // LIST USER
    // =====================================================
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter role
        if ($request->role && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    // =====================================================
    // CREATE FORM
    // =====================================================
    public function create()
    {
        return view('users.create');
    }

    // =====================================================
    // STORE USER BARU
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,petugas',
            'foto'     => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Simpan foto profil
        if ($request->hasFile('foto')) {
            $path = $request->foto->store('user_profile', 'public');

            Media::create([
                'ref_table'  => 'user_profile',
                'ref_id'     => $user->id,
                'file_url'   => $path,
                'mime_type'  => $request->foto->getClientMimeType(),
                'caption'    => 'Foto Profil User',
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    // =====================================================
    // EDIT USER
    // =====================================================
    public function edit($id)
    {
        $user = User::with('fotoProfil')->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // =====================================================
    // UPDATE USER
    // =====================================================
    public function update(Request $request, $id)
    {
        $user = User::with('fotoProfil')->findOrFail($id);

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|in:admin,petugas',
            'foto'  => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->password) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // FOTO BARU
        if ($request->hasFile('foto')) {

            if ($user->fotoProfil) {
                Storage::disk('public')->delete($user->fotoProfil->file_url);
                $user->fotoProfil->delete();
            }

            $path = $request->foto->store('user_profile', 'public');

            Media::create([
                'ref_table'  => 'user_profile',
                'ref_id'     => $user->id,
                'file_url'   => $path,
                'mime_type'  => $request->foto->getClientMimeType(),
                'caption'    => 'Foto Profil User',
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    // =====================================================
    // DELETE USER
    // =====================================================
    public function destroy($id)
    {
        $user = User::with('fotoProfil')->findOrFail($id);

        if ($user->fotoProfil) {
            Storage::disk('public')->delete($user->fotoProfil->file_url);
            $user->fotoProfil->delete();
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }

    // =====================================================
    // CHECK EMAIL (AJAX)
    // =====================================================
    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    // =====================================================
    // PROFIL SAYA
    // =====================================================
    public function profil()
    {
        $userId = session('user_id'); // ← Fix autentikasi

        if (! $userId) {
            return redirect()->route('login.form')->with('error', 'Silakan login kembali.');
        }

        $user = User::with('fotoProfil')->findOrFail($userId);

        return view('users.profil', compact('user'));
    }

    // =====================================================
    // UPDATE PROFIL SAYA
    // =====================================================
    public function updateProfil(Request $request)
    {
        $userId = session('user_id');

        $user = User::with('fotoProfil')->findOrFail($userId);

        $request->validate([
            'name'     => 'required',
            'email'    => "required|email|unique:users,email," . $user->id,
            'password' => 'nullable|min:6',
            'foto'     => 'nullable|image|max:2048',
        ]);

        // Update basic data
        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // FOTO PROFIL
        if ($request->hasFile('foto')) {

            if ($user->fotoProfil) {
                Storage::disk('public')->delete($user->fotoProfil->file_url);
                $user->fotoProfil->delete();
            }

            $path = $request->foto->store('user_profile', 'public');

            Media::create([
                'ref_table'  => 'user_profile',
                'ref_id'     => $user->id,
                'file_url'   => $path,
                'mime_type'  => $request->foto->getClientMimeType(),
                'caption'    => 'Foto Profil User',
                'sort_order' => 1,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
