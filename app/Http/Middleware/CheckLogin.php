<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Jika belum login → arahkan ke login
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login.form')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
