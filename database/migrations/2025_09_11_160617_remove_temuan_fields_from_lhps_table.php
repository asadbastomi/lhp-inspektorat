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
            $table->dropColumn(['temuan', 'rincian_rekomendasi', 'besaran_temuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhps', function (Blueprint $table) {
            $table->text('temuan')->nullable()->after('file_p2hp');
            $table->text('rincian_rekomendasi')->nullable()->after('temuan');
            $table->decimal('besaran_temuan', 15, 2)->nullable()->after('rincian_rekomendasi');
        });
    }
};
