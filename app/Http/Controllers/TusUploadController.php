<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TusPhp\Tus\Server as TusServer;
use TusPhp\Tus\File as TusFile;
use TusPhp\Tus\TusServer as BaseTusServer;

class TusUploadController extends Controller
{
    protected $server;

    public function __construct()
    {
        $this->middleware(['auth', 'irban']);
        
        // Initialize TUS server
        $this->server = new TusServer('file');
        
        // Configure TUS server
        $this->configureTusServer();
    }

    protected function configureTusServer()
    {
        // Set upload directory with proper permissions
        $uploadDir = storage_path('app/tmp-uploads');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $this->server->setUploadDir($uploadDir);
        
        // Set API path - must match the route
        $this->server->setApiPath('/tus-upload');
        
        // Set upload key generator
        $this->server->setNameGenerator(function () {
            return 'file_' . uniqid();
        });
        
        // Enable file expiration (24 hours)
        $this->server->setExpiration(86400);
        
        // Enable CORS if needed
        $this->server->setHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, HEAD, PATCH, DELETE',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Content-Length, Upload-Key, Upload-Length, Upload-Offset, Tus-Resumable, Upload-Metadata',
            'Access-Control-Expose-Headers' => 'Upload-Key, Upload-Length, Upload-Offset, Location, Upload-Metadata, Tus-Version, Tus-Resumable, Tus-Extension, Tus-Max-Size'
        ]);
    }

    public function handleUpload(Request $request)
    {
        try {
            Log::info('TUS Request Headers:', $request->headers->all());
            Log::info('TUS Request Method:', [$request->method()]);
            
            // Handle preflight OPTIONS request
            if ($request->isMethod('OPTIONS')) {
                return response('', 200)
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, HEAD, PATCH, DELETE')
                    ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Content-Length, Upload-Key, Upload-Length, Upload-Offset, Tus-Resumable, Upload-Metadata')
                    ->header('Access-Control-Expose-Headers', 'Upload-Key, Upload-Length, Upload-Offset, Location, Upload-Metadata, Tus-Version, Tus-Resumable, Tus-Extension, Tus-Max-Size');
            }
            
            // Process the TUS request
            $response = $this->server->serve();
            
            // If this is a completed upload, move the file to permanent storage
            if ($request->method() === 'POST' && $response->getStatusCode() === 201) {
                $uploadKey = $this->server->getUploadKey();
                $file = new TusFile($this->server->getUploadDir() . '/' . $uploadKey);
                
                // Generate a unique filename with original extension
                $originalName = $file->getMetadata('filename') ?? 'uploaded_file';
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                if (empty($extension)) {
                    $extension = 'pdf'; // Default extension
                }
                
                $newFilename = Str::random(40) . '.' . $extension;
                
                // Create year/month directory structure
                $path = 'lhp/' . date('Y/m');
                $fullPath = $path . '/' . $newFilename;
                
                // Ensure directory exists
                Storage::makeDirectory($path);
                
                // Move file to permanent storage
                Storage::put($fullPath, file_get_contents($file->getPath()));
                
                // Clean up temporary file
                if (file_exists($file->getPath())) {
                    unlink($file->getPath());
                }
                
                // Return success response with file info
                $responseData = [
                    'success' => true,
                    'path' => $fullPath,
                    'filename' => $originalName,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
                    'upload_id' => $uploadKey
                ];
                
                Log::info('File upload completed successfully', $responseData);
                
                return response()->json($responseData);
            }
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('TUS Upload Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'request' => [
                    'method' => $request->method(),
                    'headers' => $request->headers->all(),
                    'input' => $request->all()
                ]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
                'exception' => get_class($e)
            ], 500);
        }
    }
}
