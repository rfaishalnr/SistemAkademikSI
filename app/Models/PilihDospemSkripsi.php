<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilihDospemSkripsi extends Model
{
    protected $table = 'pengajuan_skripsis';

    protected $fillable = [
        'dosen_pembimbing_id',
        'status_pembimbing',
        'catatan_pembimbing',
    ];

    public function dosenPembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
