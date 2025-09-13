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
        Schema::create('temuans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lhp_id')->constrained()->onDelete('cascade');
            $table->string('jenis_pengawasan');
            $table->text('rincian');
            $table->text('rekomendasi')->nullable();
            $table->decimal('besaran', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temuans');
    }
};
