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
        // First, delete all existing tindak lanjut records
        \App\Models\TindakLanjut::truncate();

        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            if (Schema::hasColumn('tindak_lanjuts', 'lhp_id')) {
                // Drop the old foreign key by dropping the column
                $table->dropConstrainedForeignId('lhp_id');
            }

            if (!Schema::hasColumn('tindak_lanjuts', 'rekomendasi_id')) {
                // Add the new foreign key
                $table->foreignUuid('rekomendasi_id')->constrained()->onDelete('cascade')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            // Drop the new foreign key and column
            $table->dropConstrainedForeignId('rekomendasi_id');

            // Add the old foreign key and column back
            $table->foreignUuid('lhp_id')->constrained()->onDelete('cascade')->after('id');
        });
    }
};
