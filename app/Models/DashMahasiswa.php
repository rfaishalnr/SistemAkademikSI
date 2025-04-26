<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashMahasiswa extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'mahasiswas';

    protected $fillable = ['nama', 'npm', 'email', 'password'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];
    
    public function getAuthIdentifierName()
    {
        return 'npm'; // Gunakan 'npm' atau 'email' sesuai kebutuhan
    }
}