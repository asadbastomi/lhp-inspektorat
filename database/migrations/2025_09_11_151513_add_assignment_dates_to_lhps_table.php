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
        Schema::table('lhps', function (Blueprint $table) {
            $table->date('tgl_surat_tugas')->nullable()->after('nomor_surat_tugas');
            $table->date('tgl_awal_penugasan')->nullable()->after('lama_penugasan');
            $table->date('tgl_akhir_penugasan')->nullable()->after('tgl_awal_penugasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhps', function (Blueprint $table) {
            $table->dropColumn(['tgl_surat_tugas', 'tgl_awal_penugasan', 'tgl_akhir_penugasan']);
        });
    }
};
