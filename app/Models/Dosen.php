<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\User;

class Dosen extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['name','nomor_hp', 'nidn', 'email', 'password', 'prodi', 'peran'];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($dosen) {
            User::create([
                'name' => $dosen->name,
                'email' => $dosen->email,
                'password' => Hash::make('password123'), // Password default
                'role' => 'dosen',
            ]);
        });

        static::deleted(function ($dosen) {
            User::where('email', $dosen->email)->delete();
        });
    }
}
