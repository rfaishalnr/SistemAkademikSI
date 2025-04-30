<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Ketentuan;
use App\Models\User;
use App\Models\PengajuanKP;
use App\Models\PengajuanSkripsi;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\IconPosition;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Check if user is a student (mahasiswa)
        $mahasiswa = Mahasiswa::where('email', $user->email)->first();
        if ($mahasiswa) {
            return $this->getMahasiswaStats($mahasiswa);
        }
        
        // Check if user is a lecturer (dosen)
        $dosen = Dosen::where('email', $user->email)->first();
        if ($dosen) {
            return $this->getDosenStats($dosen);
        }
        
        // Default: assume user is admin
        return $this->getAdminStats();
    }
    
    protected function getAdminStats(): array
    {
        return [
            Stat::make('Jumlah Admin', User::count())
                ->icon('heroicon-o-user-circle')
                ->color('primary'),
            
            Stat::make('Jumlah Dosen', Dosen::count())
                ->icon('heroicon-o-academic-cap')
                ->color('success'),
            
            Stat::make('Jumlah Mahasiswa', Mahasiswa::count())
                ->icon('heroicon-o-users')
                ->color('info'),
            
            Stat::make('Total Ketentuan', Ketentuan::count())
                ->icon('heroicon-o-document-text')
                ->color('warning'),
                
            Stat::make('Pengajuan KP', PengajuanKP::count())
                ->icon('heroicon-o-briefcase')
                ->color('danger'),
                
            Stat::make('Pengajuan Skripsi', PengajuanSkripsi::count())
                ->icon('heroicon-o-academic-cap')
                ->color('success'),
        ];
    }
    
    protected function getMahasiswaStats($mahasiswa): array
    {
        // Get KP application for this student
        $kpPengajuan = PengajuanKP::where('mahasiswa_id', $mahasiswa->id)->first();
        $kpStatus = $kpPengajuan ? json_decode($kpPengajuan->statuses, true) : [];
        $kpProgress = $kpPengajuan ? $this->calculateProgress($kpStatus) : 0;
        
        // Get Skripsi application for this student
        $skripsiPengajuan = PengajuanSkripsi::where('mahasiswa_id', $mahasiswa->id)->first();
        $skripsiStatus = $skripsiPengajuan ? json_decode($skripsiPengajuan->statuses, true) : [];
        $skripsiProgress = $skripsiPengajuan ? $this->calculateProgress($skripsiStatus) : 0;
        
        // Get assigned supervisors for thesis
        $pembimbing1 = null;
        $pembimbing2 = null;
        
        if ($skripsiPengajuan && $skripsiPengajuan->dosen_pembimbing_id) {
            $pembimbing1 = Dosen::find($skripsiPengajuan->dosen_pembimbing_id);
        }
        
        if ($skripsiPengajuan && $skripsiPengajuan->dosen_pembimbing_2_id) {
            $pembimbing2 = Dosen::find($skripsiPengajuan->dosen_pembimbing_2_id);
        }
        
        return [
            Stat::make('NPM', $mahasiswa->npm)
                ->icon('heroicon-o-identification')
                ->color('primary'),
                
            Stat::make('Program Kerja Praktik', $kpPengajuan ? 'Diajukan' : 'Belum Diajukan')
                ->description($kpPengajuan ? 'Progress: ' . $kpProgress . '%' : 'Belum ada pengajuan')
                ->icon('heroicon-o-briefcase')
                ->color($kpPengajuan ? 'success' : 'warning'),
                
            Stat::make('Skripsi', $skripsiPengajuan ? 'Diajukan' : 'Belum Diajukan')
                ->description($skripsiPengajuan ? 'Progress: ' . $skripsiProgress . '%' : 'Belum ada pengajuan')
                ->icon('heroicon-o-academic-cap')
                ->color($skripsiPengajuan ? 'success' : 'warning'),
                
            Stat::make('Pembimbing 1', $pembimbing1 ? $pembimbing1->name : 'Belum Ditentukan')
                ->icon('heroicon-o-user')
                ->color($pembimbing1 ? 'success' : 'warning'),
                
            Stat::make('Pembimbing 2', $pembimbing2 ? $pembimbing2->name : 'Belum Ditentukan')
                ->icon('heroicon-o-user')
                ->color($pembimbing2 ? 'success' : 'warning'),
        ];
    }
    
    protected function getDosenStats($dosen): array
    {
        // Count how many students this lecturer is supervising as first supervisor
        $countPembimbing1 = PengajuanSkripsi::where('dosen_pembimbing_id', $dosen->id)->count();
        
        // Count how many students this lecturer is supervising as second supervisor
        $countPembimbing2 = PengajuanSkripsi::where('dosen_pembimbing_2_id', $dosen->id)->count();
        
        // Count pending requests for this lecturer
        $pendingRequests = PengajuanSkripsi::where('dosen_pembimbing_id', $dosen->id)
            ->where(function($query) {
                $query->whereNull('status_pembimbing_2')
                      ->orWhere('status_pembimbing_2', 'pending');
            })->count();
            
        return [
            Stat::make('NIDN', $dosen->nidn)
                ->icon('heroicon-o-identification')
                ->color('primary'),
                
            Stat::make('Program Studi', $dosen->prodi)
                ->icon('heroicon-o-academic-cap')
                ->color('info'),
                
            Stat::make('Peran', $dosen->peran ?? 'Belum Ditentukan')
                ->icon('heroicon-o-user-circle')
                ->color('success'),
                
            Stat::make('Mahasiswa Bimbingan (Pembimbing 1)', $countPembimbing1)
                ->icon('heroicon-o-users')
                ->color('warning'),
                
            Stat::make('Mahasiswa Bimbingan (Pembimbing 2)', $countPembimbing2)
                ->icon('heroicon-o-users')
                ->color('warning'),
                
            Stat::make('Permintaan Persetujuan', $pendingRequests)
                ->icon('heroicon-o-bell-alert')
                ->color($pendingRequests > 0 ? 'danger' : 'success'),
        ];
    }
    
    /**
     * Calculate progress percentage based on status values
     */
    private function calculateProgress($statusArray): int
    {
        if (empty($statusArray)) {
            return 0;
        }
        
        $total = count($statusArray);
        $approved = 0;
        
        foreach ($statusArray as $status) {
            if ($status === 'approved') {
                $approved++;
            }
        }
        
        return round(($approved / $total) * 100);
    }
}