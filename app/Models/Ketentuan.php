<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketentuan extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'ketentuans'; // Sesuaikan dengan nama tabel
    protected $fillable = ['nama_file', 'jenis', 'persyaratan', 'prosedur', 'timeline'];
 
    public static function boot()
    {
        parent::boot();
    }
}
