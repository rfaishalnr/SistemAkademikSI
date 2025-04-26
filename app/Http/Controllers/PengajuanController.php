<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKP;
use App\Models\PengajuanSkripsi;
use App\Models\Ketentuan;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;


class PengajuanController extends Controller
{
    // Halaman pengajuan KP
    public function pengajuanKP()
    {
        // Cari mahasiswa berdasarkan ID user yang sedang login
        $mahasiswa = Mahasiswa::where('id', Auth::id())->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $ketentuans = Ketentuan::where('jenis', 'KP')->get();
        $pengajuans = PengajuanKP::where('mahasiswa_id', $mahasiswa->id)->get();

        return view('pengajuan.kp', compact('ketentuans', 'pengajuans'));
    }







    
    public function uploadKP(Request $request)
    {
        $request->validate([
            'files.*' => 'file|max:2048', // Validasi file
        ]);
    
        // Cek apakah mahasiswa sudah memiliki pengajuan KP yang aktif
        $existingPengajuan = PengajuanKP::where('mahasiswa_id', Auth::id())
                                ->first();
        
        $uploadedFiles = [];
        
        // Cek jika ada file yang diupload
        if ($request->hasFile('files')) {
            // Jika pengajuan sudah ada dan statusnya 'rejected', hapus file lama
            if ($existingPengajuan) {
                $oldFiles = json_decode($existingPengajuan->files, true);
                
                // Hapus file lama dari storage
                foreach ($oldFiles as $oldFile) {
                    if (Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }
            
            // Upload file baru
            foreach ($request->file('files') as $file) {
                // Nama file dengan prefix waktu agar unik
                $filename = time() . '_' . $file->getClientOriginalName(); 
                // Menyimpan file di folder pengajuan_kp dan public disk
                $path = $file->storeAs('pengajuan_kp', $filename, 'public'); 
                
                // Simpan path file dalam array
                $uploadedFiles[] = $path;
            }
        }
        
        // Pastikan status untuk setiap file
        $countFiles = count($uploadedFiles);
        $statuses = array_fill(0, max(1, $countFiles), 'sent');
        
        // Jika pengajuan sudah ada, update data pengajuan
        if ($existingPengajuan) {
            $existingPengajuan->update([
                'files' => json_encode($uploadedFiles),
                'statuses' => $statuses,
            ]);
            
            $message = 'Pengajuan KP berhasil diperbarui.';
        } else {
            // Jika belum ada, buat pengajuan baru
            PengajuanKP::create([
                'mahasiswa_id' => Auth::id(),  // ID mahasiswa yang sedang login
                'files' => json_encode($uploadedFiles),  // Simpan array path file sebagai JSON
                'statuses' => $statuses, // Set status 'sent' untuk setiap file
            ]);
            
            $message = 'Pengajuan KP berhasil dikirim.';
        }
        
        return back()->with('success', $message);
    }
    





    // GET: Form pengajuan skripsi
    public function pengajuanSkripsi()
    {
        // Ambil data mahasiswa yang sedang login
        $mahasiswa = Mahasiswa::where('id', auth('mahasiswa')->id())->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $ketentuans = Ketentuan::where('jenis', 'Skripsi')->get();
        $pengajuans = PengajuanSkripsi::where('mahasiswa_id', $mahasiswa->id)->get();
        $dosens = \App\Models\Dosen::where('peran', 'Pembimbing')->get();

        return view('pengajuan.ta', compact('ketentuans', 'pengajuans', 'dosens'));
    }








    public function submitTA(Request $request)
    {
        $mahasiswa = auth('mahasiswa')->user();
    
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx',
        ]);
    
        $files = [];
        $statuses = [];
    
        // Mengecek apakah mahasiswa sudah mengajukan skripsi sebelumnya
        $pengajuan = PengajuanSkripsi::where('mahasiswa_id', $mahasiswa->id)->first();
    
        if ($pengajuan) {
            // Menghapus file yang sudah ada dari storage
            $existingFiles = json_decode($pengajuan->files, true);
            foreach ($existingFiles as $existingFile) {
                if (Storage::disk('public')->exists($existingFile['file'])) {
                    Storage::disk('public')->delete($existingFile['file']);
                }
            }
    
            // Menghapus status yang sudah ada
            $pengajuan->delete();
        }
    
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('skripsi', 'public');
                $files[] = [
                    'file' => $path,
                    'nama_berkas' => $file->getClientOriginalName(),
                ];
                $statuses[] = 'sent';
            }
        }
    
        // Menyimpan pengajuan skripsi yang baru
        PengajuanSkripsi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'files' => json_encode($files),
            'statuses' => $statuses,
        ]);
    
        return redirect()->back()->with('success', 'Pengajuan Skripsi berhasil dikirim.');
    }
    












    public function pilihDosenPembimbing(Request $request, $id)
    {
        $request->validate([
            'dosen_pembimbing_id' => 'required|exists:dosens,id',
            'dosen_pembimbing_2_id' => 'required|exists:dosens,id|different:dosen_pembimbing_id',
        ]);
    
        $pengajuan = PengajuanSkripsi::findOrFail($id);
    
        // Cek apakah semua berkas sudah diterima
        $statuses = is_array($pengajuan->statuses)
            ? $pengajuan->statuses
            : json_decode($pengajuan->statuses, true) ?? [];
    
        $isAllAccepted = !empty($statuses) && collect($statuses)->every(fn($s) => $s === 'accepted');
    
        if (!$isAllAccepted) {
            return redirect()->back()->with('error', 'Tidak dapat memilih pembimbing sebelum semua berkas diterima.');
        }
    
        // Pemeriksaan dosen pembimbing 1
        $updateData = [];
        
        // Jika ada perubahan dosen pembimbing 1 atau sebelumnya ditolak
        if ($pengajuan->dosen_pembimbing_id != $request->dosen_pembimbing_id || 
            $pengajuan->status_pembimbing === 'rejected') {
            
            // Jika sebelumnya ditolak, tidak boleh pilih dosen yang sama
            if ($pengajuan->status_pembimbing === 'rejected' && 
                $pengajuan->dosen_pembimbing_id == $request->dosen_pembimbing_id) {
                return redirect()->back()->with('error', 'Silakan pilih dosen pembimbing 1 yang berbeda karena dosen sebelumnya telah menolak.');
            }
            
            $updateData['dosen_pembimbing_id'] = $request->dosen_pembimbing_id;
            $updateData['status_pembimbing'] = 'pending';
            $updateData['catatan_pembimbing'] = null;
        }
        
        // Pemeriksaan dosen pembimbing 2
        // Jika ada perubahan dosen pembimbing 2 atau sebelumnya ditolak
        if ($pengajuan->dosen_pembimbing_2_id != $request->dosen_pembimbing_2_id || 
            $pengajuan->status_pembimbing_2 === 'rejected') {
            
            // Jika sebelumnya ditolak, tidak boleh pilih dosen yang sama
            if ($pengajuan->status_pembimbing_2 === 'rejected' && 
                $pengajuan->dosen_pembimbing_2_id == $request->dosen_pembimbing_2_id) {
                return redirect()->back()->with('error', 'Silakan pilih dosen pembimbing 2 yang berbeda karena dosen sebelumnya telah menolak.');
            }
            
            $updateData['dosen_pembimbing_2_id'] = $request->dosen_pembimbing_2_id;
            $updateData['status_pembimbing_2'] = 'pending';
            $updateData['catatan_pembimbing_2'] = null;
        }
        
        // Pastikan dosen pembimbing 1 dan 2 tidak sama
        if ($request->dosen_pembimbing_id == $request->dosen_pembimbing_2_id) {
            return redirect()->back()->with('error', 'Dosen pembimbing 1 dan dosen pembimbing 2 tidak boleh sama.');
        }
    
        // Update data jika ada perubahan
        if (!empty($updateData)) {
            $pengajuan->update($updateData);
        }
    
        return redirect()->back()->with('success', 'Dosen pembimbing berhasil disimpan dan menunggu konfirmasi.');
    }
}
