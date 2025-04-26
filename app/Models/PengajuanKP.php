<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKP extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_k_p_s';
    protected $fillable = ['mahasiswa_id', 'files', 'statuses'];
    
    protected $casts = [
        'files' => 'array',
        'statuses' => 'array',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
