<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketentuan extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'ketentuans'; // Sesuaikan dengan nama tabel
    protected $fillable = ['nama_file', 'jenis', 'persyaratan', 'prosedur', 'timeline','panduan','file_panduan'];
 
    public static function boot()
    {
        parent::boot();
    }


    public function setFilePanduanAttribute($value)
    {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            $originalName = $value->getClientOriginalName();
            $path = $value->storeAs('panduan', $originalName, 'public');
            $this->attributes['file_panduan'] = $path;
        } else {
            $this->attributes['file_panduan'] = $value;
        }
    }
}
