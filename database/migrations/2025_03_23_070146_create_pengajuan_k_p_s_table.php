<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengajuan_k_p_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade'); // Hubungkan dengan user (mahasiswa)
            $table->longtext('files'); // Menyimpan file dalam bentuk JSON
            $table->string('statuses')->nullable(); // Status tiap file
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_k_p_s');
    }
};
