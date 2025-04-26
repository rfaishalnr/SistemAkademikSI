<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;


class LaporanKpController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $file = $request->file('file');

        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . $file->getClientOriginalName();


        $path = $file->storeAs('laporan_kp', $filename, 'public');

        // Simpan ke database
        $laporan = new LaporanKp();
        $laporan->mahasiswa_id = Auth::guard('mahasiswa')->id();
        $laporan->file = $path;
        $laporan->save();

        return back()->with('success', 'Laporan berhasil diunggah.');
    }


    // UPDATE NILAI KE DATABASE KAMPUS
    // public function updateNilai(Request $request, $id)
    // {
    //     $request->validate([
    //         'nilai' => 'required|numeric|min:0|max:100',
    //     ]);

    //     $laporan = LaporanKp::findOrFail($id);
    //     $laporan->nilai = $request->nilai;
    //     $laporan->save();

    //     $mahasiswa = $laporan->mahasiswa; // pastikan relasi mahasiswa() ada

    //     try {
    //         $response = Http::post('https://api.external.com/nilai-kp', [
    //             'laporan_id' => $laporan->id,
    //             'mahasiswa_id' => $mahasiswa->id,
    //             'nama' => $mahasiswa->nama,
    //             'nilai' => $laporan->nilai,
    //             'tanggal' => now()->toDateTimeString(),
    //         ]);

    //         if ($response->successful()) {
    //             return back()->with('success', 'Nilai berhasil disimpan dan dikirim ke sistem lain.');
    //         } else {
    //             return back()->with('warning', 'Nilai disimpan, tapi gagal dikirim ke sistem lain.');
    //         }
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Terjadi kesalahan saat mengirim ke sistem lain: ' . $e->getMessage());
    //     }
    // }
}
