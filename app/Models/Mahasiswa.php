<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $table = 'mahasiswas';
    protected $guard = 'mahasiswa';
    protected $fillable = [
        'name',
        'npm',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($mahasiswa) {
    //         // Buat akun user ketika mahasiswa baru dibuat
    //         User::create([
    //             'name' => $mahasiswa->name,
    //             'email' => $mahasiswa->email,
    //             'password' => Hash::make($mahasiswa->password), // Enkripsi password
    //             'role' => 'mahasiswa', // Set peran mahasiswa
    //         ]);
    //     });
    // }

    // Relasi ke Pengajuan KP
    public function pengajuanKP()
    {
        return $this->hasMany(PengajuanKP::class, 'mahasiswa_id');
    }
}
