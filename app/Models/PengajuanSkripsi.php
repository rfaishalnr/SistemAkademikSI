<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSkripsi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_pembimbing_id',
        'dosen_pembimbing_2_id',
        'files',
        'statuses',
        'status_pembimbing',
        'catatan_pembimbing',
        'status_pembimbing_2',
        'catatan_pembimbing_2',
    ];

    protected $casts = [
        'files' => 'array',
        'statuses' => 'array',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
    
    public function dosen_pembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }

    
    // In PengajuanSkripsi model
    // public function dosen()
    // {
    //     return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    // }


    // public function dosenPembimbing2()
    // {
    //     return $this->belongsTo(Dosen::class, 'dosen_pembimbing_2_id');
    // }
    
}
