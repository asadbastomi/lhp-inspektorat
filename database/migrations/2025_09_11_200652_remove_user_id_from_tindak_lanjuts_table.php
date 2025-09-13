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
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            // Drop foreign key constraint first
            if (Schema::hasColumn('tindak_lanjuts', 'user_id')) {
                // The foreign key name is conventionally `tablename_column_foreign`
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            if (!Schema::hasColumn('tindak_lanjuts', 'user_id')) {
                $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }
};
