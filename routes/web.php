<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanKpController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminLaporanKpController;

// Redirect halaman utama ke login mahasiswa
Route::get('/', function () {
    return redirect()->route('mahasiswa.login');
});

// Prefix untuk mahasiswa
// Group route untuk mahasiswa
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route dashboard dengan middleware auth
    Route::middleware('auth:mahasiswa')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    });

    Route::middleware('auth:mahasiswa')->group(function () {
        Route::get('/pengajuan-kp', [PengajuanController::class, 'pengajuanKP'])->name('pengajuan-kp');
        Route::post('/submit-kp', [PengajuanController::class, 'uploadKP'])->name('submit-kp');
        Route::post('/upload-laporan-kp', [LaporanKpController::class, 'upload'])->name('upload-laporan-kp');
        Route::post('/laporan-kp/{id}/nilai', [LaporanKpController::class, 'updateNilai'])->name('laporan-kp.nilai'); //(UPDATE NILAI KE DATABSE)
    
        Route::get('/pengajuan-ta', [PengajuanController::class, 'pengajuanSkripsi'])->name('pengajuan-ta');
        Route::post('/submit-ta', [PengajuanController::class, 'submitTA'])->name('submit-ta');
        Route::post('/pilih-dosen-pembimbing/{id}', [PengajuanController::class, 'pilihDosenPembimbing'])->name('pilih-pembimbing');  
        
    });
    
});
