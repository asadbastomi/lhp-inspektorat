<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UploadController extends Controller
{
    public function __construct()
    {
        // Increase PHP's max execution time and memory limit for large files
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        ini_set('post_max_size', '500M');
        ini_set('upload_max_filesize', '500M');
        ini_set('max_execution_time', '600');
        
        // Ensure the uploads directory exists and is writable
        $uploadPath = storage_path('app/public/uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        $this->middleware(['auth']);
    }
    
    /**
     * Handle Livewire file uploads with progress
     */
    public function livewireUpload(Request $request)
    {
        // Log raw input for debugging
        Log::info('Livewire upload request received', [
            'inputs' => $request->except(['file']), // Exclude file content from logs
            'hasFile' => $request->hasFile('file'),
            'allFiles' => array_keys($request->allFiles())
        ]);
        
        try {
            // Check if file exists
            if (!$request->hasFile('file')) {
                Log::error('No file in request', [
                    'files' => $request->allFiles(),
                    'all_input' => $request->all()
                ]);
                throw new \Exception('No file uploaded');
            }
            
            // Get the field_name and lhp_id from the request
            $fieldName = $request->input('field_name');
            if (!$fieldName) {
                throw new \Exception('Field name is required');
            }
            
            $lhpId = $request->input('lhp_id');
            if (!$lhpId) {
                throw new \Exception('LHP ID is required');
            }
            
            // Validate the field name
            $allowedFields = ['file_surat_tugas', 'file_lhp', 'file_kertas_kerja', 'file_review_sheet', 'file_nota_dinas'];
            if (!in_array($fieldName, $allowedFields)) {
                throw new \Exception('Invalid field name: ' . $fieldName);
            }
            
            // Validate the file
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:204800', // 200MB max
                'lhp_id' => 'required|exists:lhps,id'
            ]);
            
            $file = $request->file('file');
            
            // Store original file info before processing
            $originalName = $file->getClientOriginalName();
            $originalSize = $file->getSize();
            $originalMimeType = $file->getMimeType();
            
            // Generate a unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $extension;
            
            // Ensure the directory exists
            $directory = 'lhp-documents';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            // Store the file
            $path = $file->storeAs($directory, $filename, 'public');
            
            if (!$path) {
                throw new \Exception('Failed to save file to storage');
            }
            
            // Verify file was stored
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception('File was not properly stored');
            }
            
            // Update the database record
            $lhp = \App\Models\Lhp::findOrFail($lhpId);
            
            // Delete old file if it exists
            if ($lhp->$fieldName && Storage::disk('public')->exists($lhp->$fieldName)) {
                Storage::disk('public')->delete($lhp->$fieldName);
                Log::info('Old file deleted', ['path' => $lhp->$fieldName]);
            }
            
            // Save the new file path
            $lhp->$fieldName = $path;
            $lhp->save();
            
            Log::info('File uploaded and database updated successfully', [
                'lhp_id' => $lhpId,
                'field' => $fieldName,
                'path' => $path,
                'original_name' => $originalName,
                'stored_name' => $filename,
                'size' => $originalSize
            ]);
            
            // Return success response
            return response()->json([
                'success' => true,
                'path' => $path,
                'filename' => $filename,
                'field' => $fieldName,
                'url' => Storage::url($path),
                'original_name' => $originalName,
                'size' => $originalSize,
                'mime_type' => $originalMimeType,
                'message' => 'File berhasil diunggah.'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error during upload', [
                'errors' => $e->errors(),
                'field' => $fieldName ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'field' => $fieldName ?? 'unknown',
                'lhp_id' => $lhpId ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
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
            $out = null; // Set to null after closing
            
            // Store the file directly using PHP functions
            $finalPath = $fullStoragePath . '/' . $newFilename;
            
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
                
                // Try copy as fallback
                if (file_exists($tempFile) && @copy($tempFile, $finalPath)) {
                    @unlink($tempFile);
                    Log::info('Used copy as fallback for file move');
                } else {
                    throw new \Exception(sprintf(
                        'Failed to move file to final location: %s',
                        $errorMsg
                    ));
                }
            }
            
            // Set proper permissions
            if (!@chmod($finalPath, 0664)) {
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
            
            // Clean up chunks
            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkFile = $chunkPath . '/' . $filename . '.part' . $i;
                if (file_exists($chunkFile)) {
                    @unlink($chunkFile);
                }
            }
            
            // Remove chunk directory
            if (is_dir($chunkPath)) {
                @rmdir($chunkPath);
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
            if ($tempFile && file_exists($tempFile)) {
                @unlink($tempFile);
            }
            
            if (isset($finalPath) && file_exists($finalPath)) {
                @unlink($finalPath);
            }
            
            // Clean up chunks on error
            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkFile = $chunkPath . '/' . $filename . '.part' . $i;
                if (file_exists($chunkFile)) {
                    @unlink($chunkFile);
                }
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to process uploaded file: ' . $e->getMessage()
            ], 500);
        }
    }
}