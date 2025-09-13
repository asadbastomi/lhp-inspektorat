<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TindakLanjut extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasUuids;
    protected $fillable = [
        'rekomendasi_id',
        'uraian',
        'tanggal',
        'file_name',
        'file_path',
        'file_size',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the Rekomendasi that owns the TindakLanjut
     */
    public function rekomendasi(): BelongsTo
    {
        return $this->belongsTo(Rekomendasi::class);
    }

    /**
     * Get the URL to the file
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Get the file icon based on file type
     */
    public function getFileIconAttribute(): string
    {
        return match ($this->file_type) {
            'image' => 'fa-image',
            'pdf' => 'fa-file-pdf',
            'document' => 'fa-file-word',
            'spreadsheet' => 'fa-file-excel',
            'video' => 'fa-file-video',
            'audio' => 'fa-file-audio',
            default => 'fa-file',
        };
    }

    /**
     * Get the formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    /**
     * Check if the file is an image
     */
    public function getIsImageAttribute(): bool
    {
        return $this->file_type === 'image';
    }

    /**
     * Check if the file is a PDF
     */
    public function getIsPdfAttribute(): bool
    {
        return $this->file_type === 'pdf';
    }

    /**
     * Check if the file is a document (Word, Excel, etc.)
     */
    public function getIsDocumentAttribute(): bool
    {
        return in_array($this->file_type, ['document', 'spreadsheet']);
    }

    /**
     * Check if the file is a media file (audio, video)
     */
    public function getIsMediaAttribute(): bool
    {
        return in_array($this->file_type, ['audio', 'video']);
    }
}
