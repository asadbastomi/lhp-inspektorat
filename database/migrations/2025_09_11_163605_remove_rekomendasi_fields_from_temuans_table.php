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
        Schema::table('temuans', function (Blueprint $table) {
            $table->dropColumn(['rekomendasi', 'besaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temuans', function (Blueprint $table) {
            $table->text('rekomendasi')->nullable()->after('rincian');
            $table->decimal('besaran', 15, 2)->default(0)->nullable()->after('rekomendasi');
        });
    }
};
