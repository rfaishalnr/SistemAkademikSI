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
            $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('dosens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_skripsis', function (Blueprint $table) {
            $table->dropForeign(['dosen_pembimbing_id']);
            $table->dropColumn('dosen_pembimbing_id');
        });
    }
};
