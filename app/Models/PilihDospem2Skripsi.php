<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilihDospem2Skripsi extends Model
{
    protected $table = 'pengajuan_skripsis';

    protected $fillable = [
        'dosen_pembimbing_2_id',
        'status_pembimbing_2',
        'catatan_pembimbing_2',
    ];

    public function dosenPembimbing2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_2_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
