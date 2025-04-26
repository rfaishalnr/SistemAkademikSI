<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_skripsis', function (Blueprint $table) {
            $table->unsignedBigInteger('dosen_pembimbing_2_id')->nullable();
            $table->enum('status_pembimbing_2', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('catatan_pembimbing_2')->nullable();

            $table->foreign('dosen_pembimbing_2_id')->references('id')->on('dosens');
        });
    }

    public function down()
    {
        Schema::table('pengajuan_skripsis', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_skripsis', 'dosen_pembimbing_2_id')) {
                $table->dropForeign(['dosen_pembimbing_2_id']);
            }

            $table->dropColumn([
                'dosen_pembimbing_2_id',
                'status_pembimbing_2',
                'catatan_pembimbing_2',
            ]);
        });
    }
};
