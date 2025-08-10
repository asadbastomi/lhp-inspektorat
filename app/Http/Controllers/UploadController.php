<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Handle Resumable.js upload requests
     */
    public function upload(Request $request)
    {
        // Create temp directory if it doesn't exist
        $tempPath = storage_path('app/chunks');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        $resumableIdentifier = $request->input('resumableIdentifier');
        $resumableFilename = $request->input('resumableFilename');
        $resumableChunkNumber = $request->input('resumableChunkNumber');
        $resumableTotalChunks = $request->input('resumableTotalChunks');
        $resumableTotalSize = $request->input('resumableTotalSize');

        if (!$resumableIdentifier || !$resumableFilename || !$resumableChunkNumber) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        // Validate file type (PDF only)
        $extension = strtolower(pathinfo($resumableFilename, PATHINFO_EXTENSION));
        if ($extension !== 'pdf') {
            return response()->json(['error' => 'Only PDF files are allowed'], 415);
        }

        // Validate file size (200MB max)
        if ($resumableTotalSize > 200 * 1024 * 1024) {
            return response()->json(['error' => 'File size exceeds 200MB limit'], 413);
        }

        $chunkPath = $tempPath . '/' . $resumableIdentifier;

        // Create chunk directory
        if (!file_exists($chunkPath)) {
            mkdir($chunkPath, 0777, true);
        }

        // Move uploaded chunk to temp directory
        $chunkFile = $chunkPath . '/' . $resumableFilename . '.part' . $resumableChunkNumber;
        
        if ($request->hasFile('file')) {
            $request->file('file')->move($chunkPath, basename($chunkFile));
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        // Check if all chunks have been uploaded
        $allChunksUploaded = true;
        for ($i = 1; $i <= $resumableTotalChunks; $i++) {
            if (!file_exists($chunkPath . '/' . $resumableFilename . '.part' . $i)) {
                $allChunksUploaded = false;
                break;
            }
        }

        // If all chunks are uploaded, combine them
        if ($allChunksUploaded) {
            return $this->combineChunks(
                $chunkPath,
                $resumableFilename,
                $resumableTotalChunks,
                $resumableIdentifier
            );
        }

        // Return success for chunk upload
        return response()->json([
            'success' => true,
            'message' => 'Chunk uploaded successfully'
        ]);
    }

    /**
     * Check if chunk exists (for resume capability)
     */
    public function check(Request $request)
    {
        $resumableIdentifier = $request->input('resumableIdentifier');
        $resumableFilename = $request->input('resumableFilename');
        $resumableChunkNumber = $request->input('resumableChunkNumber');

        if (!$resumableIdentifier || !$resumableFilename || !$resumableChunkNumber) {
            return response()->json([
                'exists' => false,
                'message' => 'Missing required parameters'
            ], 400);
        }

        $tempPath = storage_path('app/chunks');
        $chunkPath = $tempPath . '/' . $resumableIdentifier;
        $chunkFile = $chunkPath . '/' . $resumableFilename . '.part' . $resumableChunkNumber;

        if (file_exists($chunkFile)) {
            return response()->json([
                'exists' => true,
                'message' => 'Chunk exists'
            ]);
        }

        return response()->json([
            'exists' => false,
            'message' => 'Chunk not found'
        ], 404);
    }

    /**
     * Combine all chunks into final file
     */
    private function combineChunks($chunkPath, $filename, $totalChunks, $identifier)
    {
        $tempFile = null;
        $out = null;
        
        try {
            Log::info('Starting to combine chunks', [
                'chunkPath' => $chunkPath,
                'filename' => $filename,
                'totalChunks' => $totalChunks,
                'identifier' => $identifier
            ]);

            // Generate unique filename for storage
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $newFilename = 'lhp_' . date('Ymd_His') . '_' . Str::random(10) . '.' . $extension;
            
            // Create storage path with year/month structure
            $storagePath = 'lhp/' . date('Y/m');
            $fullStoragePath = storage_path('app/public/' . $storagePath);
            
            // Ensure storage directory exists with verbose error handling
            if (!file_exists($fullStoragePath)) {
                Log::info('Attempting to create directory', ['path' => $fullStoragePath]);
                
                $oldUmask = umask(0);
                $created = @mkdir($fullStoragePath, 0777, true);
                umask($oldUmask);
                
                if (!$created) {
                    $error = error_get_last();
                    $errorMsg = $error ? $error['message'] : 'Unknown error';
                    Log::error('Failed to create directory', [
                        'path' => $fullStoragePath,
                        'error' => $errorMsg,
                        'permissions' => substr(sprintf('%o', fileperms(dirname($fullStoragePath))), -4)
                    ]);
                    throw new \Exception(sprintf(
                        'Failed to create storage directory "%s": %s',
                        $fullStoragePath,
                        $errorMsg
                    ));
                }
                
                // Double check directory was created
                if (!is_dir($fullStoragePath)) {
                    throw new \Exception('Directory creation succeeded but directory does not exist: ' . $fullStoragePath);
                }
                
                Log::info('Successfully created directory', [
                    'path' => $fullStoragePath,
                    'permissions' => substr(sprintf('%o', fileperms($fullStoragePath)), -4)
                ]);
            } else {
                Log::info('Directory already exists', [
                    'path' => $fullStoragePath,
                    'permissions' => substr(sprintf('%o', fileperms($fullStoragePath)), -4),
                    'writable' => is_writable($fullStoragePath) ? 'yes' : 'no'
                ]);
            }
            
            // Create a temporary file to combine chunks
            $tempDir = sys_get_temp_dir();
            if (!is_writable($tempDir)) {
                throw new \Exception('Temporary directory is not writable: ' . $tempDir);
            }
            
            $tempFile = tempnam($tempDir, 'lhp_upload_');
            if ($tempFile === false) {
                throw new \Exception('Failed to create temporary file in: ' . $tempDir);
            }
            
            Log::debug('Created temporary file', ['tempFile' => $tempFile]);
            
            $out = fopen($tempFile, 'wb');
            if (!$out) {
                throw new \Exception('Failed to open temporary file for writing: ' . $tempFile);
            }

            // Write each chunk to the temporary file
            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkFile = $chunkPath . '/' . $filename . '.part' . $i;
                if (!file_exists($chunkFile)) {
                    fclose($out);
                    @unlink($tempFile);
                    throw new \Exception('Missing chunk ' . $i);
                }
                
                $in = fopen($chunkFile, 'rb');
                if (!$in) {
                    fclose($out);
                    @unlink($tempFile);
                    throw new \Exception('Failed to open chunk ' . $i);
                }
                
                // Copy chunk content to temporary file
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                
                fclose($in);
            }
            
            fclose($out);
            
            // Store the file directly using PHP functions
            $finalPath = $fullStoragePath . '/' . $newFilename;
            
            try {
                // Close the temp file before moving it
                if (is_resource($out)) {
                    fclose($out);
                    $out = null;
                }
                
                // Move the temp file to final location with error handling
                $moveResult = @rename($tempFile, $finalPath);
                if (!$moveResult) {
                    $error = error_get_last();
                    $errorMsg = $error ? $error['message'] : 'Unknown error';
                    
                    Log::error('Failed to move file', [
                        'from' => $tempFile,
                        'to' => $finalPath,
                        'error' => $errorMsg,
                        'temp_exists' => file_exists($tempFile) ? 'yes' : 'no',
                        'target_dir_exists' => is_dir(dirname($finalPath)) ? 'yes' : 'no',
                        'target_dir_writable' => is_writable(dirname($finalPath)) ? 'yes' : 'no'
                    ]);
                    
                    throw new \Exception(sprintf(
                        'Failed to move file to final location: %s',
                        $errorMsg
                    ));
                }
                
                // Set proper permissions
                if (!chmod($finalPath, 0664)) {
                    Log::warning('Failed to set file permissions', ['file' => $finalPath]);
                }
                    
                // Verify the file exists
                if (!file_exists($finalPath)) {
                    throw new \Exception('File was not created at expected location');
                }
                
                $storedPath = $storagePath . '/' . $newFilename;
                
                Log::info('File stored successfully', [
                    'storedPath' => $storedPath,
                    'fullPath' => $finalPath,
                    'fileSize' => filesize($finalPath) . ' bytes',
                    'fileExists' => file_exists($finalPath) ? 'yes' : 'no'
                ]);
                
            } catch (\Exception $e) {
                Log::error('Error storing file', [
                    'error' => $e->getMessage(),
                    'storagePath' => $storagePath,
                    'finalPath' => $finalPath,
                    'tempFile' => $tempFile
                ]);
                throw new \Exception('Failed to store file: ' . $e->getMessage());
            }
            
            // Clean up chunks
            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkFile = $chunkPath . '/' . $filename . '.part' . $i;
                if (file_exists($chunkFile)) {
                    unlink($chunkFile);
                }
            }
            
            // Remove chunk directory
            if (is_dir($chunkPath)) {
                rmdir($chunkPath);
            }
            
            // Get file info using Storage
            $fileSize = Storage::disk('public')->size($storedPath);
            $mimeType = Storage::disk('public')->mimeType($storedPath) ?: 'application/pdf';
            
            Log::info('File upload completed', [
                'original_name' => $filename,
                'stored_name' => $newFilename,
                'path' => $storedPath,
                'size' => $fileSize,
                'identifier' => $identifier
            ]);
            
            // Return success response with file information
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'path' => $storedPath,
                    'url' => Storage::url($storedPath),
                    'filename' => $filename,
                    'stored_name' => $newFilename,
                    'size' => $fileSize,
                    'mime_type' => $mimeType,
                    'upload_id' => $identifier
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error combining chunks: ' . $e->getMessage(), [
                'identifier' => $identifier,
                'filename' => $filename,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Clean up on error
            if (isset($finalPath) && file_exists($finalPath)) {
                @unlink($finalPath);
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to process uploaded file: ' . $e->getMessage()
            ], 500);
        }
    }
}