<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('mahasiswa.login'); // Ubah dari 'login' ke 'mahasiswa.login'
        }
    }
}
