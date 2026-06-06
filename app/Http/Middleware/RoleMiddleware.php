<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Jika belum login
        if (!session()->has('role')) {
            return redirect()->route('login.form')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika role tidak sesuai
        if (!in_array(session('role'), $roles)) {
            abort(403, 'Anda tidak memiliki akses');
        }

        return $next($request);
    }
}
