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
        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lhp_id')->constrained('lhps')->cascadeOnDelete();
            $table->string('file_name', 255)
                  ->comment('Original file name');
            $table->string('file_path', 500)
                  ->comment('File path in storage');
            $table->string('file_type', 10)
                  ->comment('File type: pdf or image');
            $table->string('mime_type', 100)
                  ->comment('MIME type of file');
            $table->unsignedInteger('file_size')
                  ->comment('File size in bytes (max 20MB per file)');
            $table->text('description')->nullable()
                  ->comment('Description of the supporting document');
            $table->timestamps();
            
            // Indexes
            $table->index('lhp_id');
            $table->index('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjuts');
    }
};
