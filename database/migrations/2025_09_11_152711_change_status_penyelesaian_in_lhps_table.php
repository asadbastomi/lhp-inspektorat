<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily change to string to allow any value
        DB::statement("ALTER TABLE lhps MODIFY COLUMN status_penyelesaian VARCHAR(255)");

        // Map old values to new values
        DB::statement("UPDATE lhps SET status_penyelesaian = 'sesuai' WHERE status_penyelesaian = 'selesai'");
        DB::statement("UPDATE lhps SET status_penyelesaian = 'belum_ditindaklanjuti' WHERE status_penyelesaian = 'belum_diproses'");

        // Change the column definition to the new enum
        DB::statement("ALTER TABLE lhps MODIFY COLUMN status_penyelesaian ENUM('belum_ditindaklanjuti', 'sesuai', 'dalam_proses') NOT NULL DEFAULT 'belum_ditindaklanjuti'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Temporarily change to string to allow any value
        DB::statement("ALTER TABLE lhps MODIFY COLUMN status_penyelesaian VARCHAR(255)");

        // Map new values back to old values
        DB::statement("UPDATE lhps SET status_penyelesaian = 'selesai' WHERE status_penyelesaian = 'sesuai'");
        DB::statement("UPDATE lhps SET status_penyelesaian = 'belum_diproses' WHERE status_penyelesaian = 'belum_ditindaklanjuti'");

        // Revert the column definition to the old enum
        DB::statement("ALTER TABLE lhps MODIFY COLUMN status_penyelesaian ENUM('selesai', 'dalam_proses', 'belum_diproses') NOT NULL DEFAULT 'belum_diproses'");
    }
};
