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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('nip');
            $table->foreignUuid('jabatan_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('pangkat_id')->nullable()->constrained('pangkats');
            $table->foreignUuid('golongan_id')->nullable()->constrained('golongans');
            $table->boolean('is_plt')->default(false)
            ->comment('Pelaksana Tugas/temporary acting position');
            $table->date('plt_start_date')->nullable();
            $table->date('plt_end_date')->nullable();
            $table->text('plt_sk_number')->nullable()
            ->comment('SK Penugasan PLT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
