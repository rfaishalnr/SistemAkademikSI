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
        Schema::table('pengajuan_skripsis', function (Blueprint $table) {
            $table->enum('status_pembimbing', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('catatan_pembimbing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_skripsis', function (Blueprint $table) {
            //
        });
    }
};
