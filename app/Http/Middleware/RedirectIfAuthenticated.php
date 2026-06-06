<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Jika session ada user_id, artinya sudah login
        if ($request->session()->has('user_id')) {
            return redirect()->route('dashboard')
                             ->with('success', 'Anda sudah login.');
        }

        return $next($request);
    }
}
