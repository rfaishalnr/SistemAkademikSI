<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserDosen extends Model
{
    public static function getCombinedQuery(): Builder
    {
        $users = User::select('id', 'name', 'email', 'password', DB::raw('"Admin" as source'));
        $dosens = Dosen::select('id', 'name', 'email', 'password', DB::raw('"Dosen" as source'));
        // $mahasiswas = Mahasiswa::select('id', 'name', 'email', 'password', DB::raw('"Mahasiswa" as role'));

        return User::query()
            ->selectRaw('*')
            ->fromSub($users->union($dosens), 'user_dosen')
            // ->fromSub($users->union($dosens)->union($mahasiswas), 'user_dosen')
            // ->fromSub($users->union($dosens)->union($mahasiswas), 'user_dosen_mahasiswa') // JIKA INGIN MENUNJUKAN MAHASISWA
            ->orderBy('id', 'asc'); // Pastikan gunakan ID dari alias 'user_dosen'
    }
}
