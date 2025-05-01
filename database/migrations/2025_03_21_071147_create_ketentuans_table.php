<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ketentuans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis'); // KP atau Skripsi
            $table->text('persyaratan')->nullable();
            $table->text('prosedur')->nullable();
            $table->text('timeline')->nullable();
            $table->text('panduan')->nullable();
            $table->string('file_panduan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketentuans');
    }
};
