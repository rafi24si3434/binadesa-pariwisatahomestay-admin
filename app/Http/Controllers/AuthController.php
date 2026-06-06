<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // FORM LOGIN
    public function loginForm()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // Validasi email & password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email atau password salah.');
        }

        // Keamanan: Regenerasi session ID untuk mencegah "Session Fixation Attack"
        $request->session()->regenerate();

        // Set session (menggunakan private method agar rapi)
        $this->setUserSession($request, $user);

        return redirect()->route('dashboard')->with('success', 'Berhasil login!');
    }

    // FORM REGISTER
    public function registerForm()
    {
        return view('auth.register');
    }

    // PROSES REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255', // Tambahan max length agar db tidak error
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,petugas',
        ]);

        // SIMPAN USER BARU
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Keamanan: Regenerasi session ID untuk user baru
        $request->session()->regenerate();

        // AUTO LOGIN (menggunakan private method)
        $this->setUserSession($request, $user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat.');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        // Keamanan: invalidate lebih aman dari flush karena membersihkan session & token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')->with('success', 'Berhasil logout.');
    }

    // ==========================================
    // PRIVATE METHODS (FUNGSI BANTUAN)
    // ==========================================

    /**
     * Mengatur session user.
     * Memisahkan fungsi ini agar tidak perlu mengetik ulang kode yang sama di login & register.
     */
    private function setUserSession(Request $request, User $user)
    {
        // Memasukkan session sekaligus dalam bentuk array agar lebih efisien
        $request->session()->put([
            'user_id'    => $user->id,
            'user_name'  => $user->name,
            'role'       => $user->role,
            'last_login' => now()->format('d M Y H:i'),
        ]);
    }
}
