<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKp extends Model
{
    protected $table = 'laporan_kp';

    protected $fillable = [
        'mahasiswa_id',
        'file_path',
        'nilai',
        'catatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

// JIKA DIPERLUKAN (UPDATE NILAI KE DATABASE LAIN)
// public function mahasiswa()
// {
//     return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
// }

    
}
