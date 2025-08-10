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
        Schema::create('lhps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('tanggal_lhp')
                    ->comment('Tanggal LHP');
            $table->string('nomor_lhp', 100)
                    ->unique()
                    ->comment('Nomor LHP');
            $table->text('judul_lhp')
                    ->comment('Judul LHP');
            $table->string('nomor_surat_tugas', 100)
                    ->comment('Nomor Surat Tugas');
            $table->date('tanggal_penugasan')
                    ->comment('Tanggal Penugasan');
            $table->integer('lama_penugasan')
                    ->comment('Lama Penugasan (dalam hari)');
            // File uploads - storing file paths
            $table->string('file_surat_tugas', 500)->nullable()
                  ->comment('File PDF surat tugas');
            $table->string('file_lhp', 500)->nullable()
                  ->comment('File PDF LHP (max 200MB, diinput oleh Admin dari irban)');
            $table->string('file_kertas_kerja', 500)->nullable()
                  ->comment('File PDF Kertas kerja pemeriksaan');
            $table->string('file_review_sheet', 500)->nullable()
                  ->comment('File PDF Review Sheet');
            $table->string('file_nota_dinas', 500)->nullable()
                  ->comment('File PDF Nota dinas');
            
            // Text fields for findings and recommendations
            $table->longText('temuan')->nullable()
                  ->comment('Temuan');
            $table->longText('rincian_rekomendasi')->nullable()
                  ->comment('Rincian rekomendasi');
            $table->text('besaran_temuan')->nullable()
                  ->comment('Besaran temuan (bisa kosong)');
            $table->longText('tindak_lanjut')->nullable()
                  ->comment('Tindak lanjut (text)');
            
            // Status completion
            $table->enum('status_penyelesaian', ['selesai', 'dalam_proses', 'belum_diproses'])
                  ->default('belum_diproses')
                  ->comment('Status penyelesaian');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();


            $table->index('tanggal_lhp');
            $table->index('tanggal_penugasan');
            $table->index('status_penyelesaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lhps');
    }
};
