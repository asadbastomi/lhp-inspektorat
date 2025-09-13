<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArsipBpkRi extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'keterangan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the file size in human readable format
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is an image
     */
    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    /**
     * Check if file is a PDF
     */
    public function getIsPdfAttribute()
    {
        return strtolower($this->file_extension) === 'pdf';
    }

    /**
     * Get file icon based on type
     */
    public function getFileIconAttribute()
    {
        $extension = strtolower($this->file_extension);

        switch ($extension) {
            case 'pdf':
                return 'fas fa-file-pdf text-red-500';
            case 'doc':
            case 'docx':
                return 'fas fa-file-word text-blue-500';
            case 'xls':
            case 'xlsx':
                return 'fas fa-file-excel text-green-500';
            case 'ppt':
            case 'pptx':
                return 'fas fa-file-powerpoint text-orange-500';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'svg':
            case 'webp':
                return 'fas fa-file-image text-purple-500';
            case 'zip':
            case 'rar':
            case '7z':
                return 'fas fa-file-archive text-yellow-500';
            default:
                return 'fas fa-file text-gray-500';
        }
    }
}
