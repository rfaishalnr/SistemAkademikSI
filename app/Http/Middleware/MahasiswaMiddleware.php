<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'mahasiswa') {
            return $next($request);
        }

        return redirect('/mahasiswa/login')->with('error', 'Akses ditolak!');
    }
}
