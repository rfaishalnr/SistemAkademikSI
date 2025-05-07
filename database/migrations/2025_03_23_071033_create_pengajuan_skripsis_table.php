<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengajuan_skripsis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->longtext('files'); //Json ke string
            $table->string('statuses')->nullable(); //Json ke string
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_skripsis');
    }
};
