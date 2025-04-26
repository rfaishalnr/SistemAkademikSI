<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('nomor_hp')->nullable()->after('name'); // Bisa ubah 'nama' sesuai urutan
        });
    }

    public function down()
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('nomor_hp');
        });
    }
};
