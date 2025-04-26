<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Ketentuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Menampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'npm' => 'required|integer|unique:mahasiswas',
            'email' => 'required|email|unique:mahasiswas',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Mahasiswa::create([
            'name' => $request->name,
            'npm' => $request->npm,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('mahasiswa.login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Menampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login mahasiswa
    public function login(Request $request)
    {
        $request->validate([
            'npm' => 'required|integer',
            'password' => 'required|string',
        ]);

        $credentials = [
            'npm' => $request->npm,
            'password' => $request->password,
        ];

        // Coba login dengan guard `mahasiswa`
        if (Auth::guard('mahasiswa')->attempt($credentials)) {
            return redirect()->route('mahasiswa.dashboard');
        }

        return back()->withErrors(['npm' => 'NPM atau password salah.']);
    }


    // Logout
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('mahasiswa.login');
    }

    // Menampilkan dashboard
    public function dashboard()
    {
        return view('dashboard.dashboard'); // Sesuai dengan struktur views

        // Ambil ketentuan dengan jenis 'KP'
        $ketentuan_kp = Ketentuan::where('jenis', 'KP')->get();

        return view('dashboard.dashboard', compact('ketentuan_kp'));
    }
}
