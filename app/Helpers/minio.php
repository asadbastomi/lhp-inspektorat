<?php

use Illuminate\Support\Facades\Storage;

function asset_minio($path)
{
    // Check if we're in local environment
    if (app()->environment('local')) {
        $cleanPath = ltrim($path, '/');
        return asset('storage/' . $cleanPath);
    }

    // Use MinIO for production
    try {
        $cleanPath = ltrim($path, '/');
        // Since the root is set to 'e-damkar', the full path will be: toms/e-damkar/{path}
        return "https://minio.banjarmasinkota.go.id/toms/lhp/{$cleanPath}";
    } catch (Exception $e) {
        // Fallback URL
        $cleanPath = ltrim($path, '/');
        return "https://minio.banjarmasinkota.go.id/toms/lhp/{$cleanPath}";
    }
}

function asset_livewire_temp($path)
{
    // Temporary files are always stored locally
    $cleanPath = ltrim($path, '/');
    return asset('storage/livewire-tmp/' . $cleanPath);
}
