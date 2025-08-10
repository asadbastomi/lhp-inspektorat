<?php

namespace App\Http\Controllers;

use App\Models\Lhp;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function __construct()
    {
        // It's generally better to set these in your php.ini file
        // but this works for a single controller.
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        
        $this->middleware(['auth']);
    }
    
    /**
     * Handle all custom file uploads from the detail page.
     */
    public function livewireUpload(Request $request)
    {
        Log::info('Upload request received', $request->except('file'));

        try {
            $request->validate([
                'file' => 'required|file|max:204800', // 200MB
                'lhp_id' => 'required|exists:lhps,id',
                'field_name' => 'required|string',
            ]);

            $file = $request->file('file');
            $lhpId = $request->input('lhp_id');
            $fieldName = $request->input('field_name');

            // --- Handle Tindak Lanjut Upload ---
            if ($fieldName === 'tindak_lanjut') {
                return $this->handleTindakLanjutUpload($request, $file, $lhpId);
            }

            // --- Handle Dokumen LHP Upload ---
            $allowedDokumenFields = ['file_surat_tugas', 'file_lhp', 'file_kertas_kerja', 'file_review_sheet', 'file_nota_dinas'];
            if (in_array($fieldName, $allowedDokumenFields)) {
                return $this->handleDokumenUpload($request, $file, $lhpId, $fieldName);
            }

            throw new \Exception('Invalid field name provided.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error during upload', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->first(),
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process and save a file for the LHP Dokumen tab.
     */
    private function handleDokumenUpload(Request $request, $file, $lhpId, $fieldName)
    {
        // Dokumen tab only accepts PDFs
        $request->validate(['file' => 'mimes:pdf']);

        $lhp = Lhp::findOrFail($lhpId);
        
        // Delete old file if it exists
        if ($lhp->$fieldName && Storage::disk('public')->exists($lhp->$fieldName)) {
            Storage::disk('public')->delete($lhp->$fieldName);
        }

        $path = $file->store('lhp-documents', 'public');
        $lhp->$fieldName = $path;
        $lhp->save();

        Log::info('Dokumen LHP uploaded successfully', ['lhp_id' => $lhpId, 'field' => $fieldName, 'path' => $path]);

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil diunggah.']);
    }

    /**
     * Process and save a file for the Tindak Lanjut tab.
     */
    private function handleTindakLanjutUpload(Request $request, $file, $lhpId)
    {
        $description = $request->input('description');
        $path = $file->store('tindak-lanjut', 'public');

        TindakLanjut::create([
            'lhp_id' => $lhpId,
            'description' => $description,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_type' => $this->getFileTypeFromMime($file->getMimeType()),
        ]);

        Log::info('Tindak Lanjut uploaded successfully', ['lhp_id' => $lhpId, 'path' => $path]);

        return response()->json(['success' => true, 'message' => 'Tindak Lanjut berhasil diunggah.']);
    }

    /**
     * Helper to determine file category from MIME type.
     */
    private function getFileTypeFromMime($mimeType)
    {
        if (Str::startsWith($mimeType, 'image/')) return 'image';
        if (Str::startsWith($mimeType, 'video/')) return 'video';
        if (Str::startsWith($mimeType, 'audio/')) return 'audio';
        if ($mimeType === 'application/pdf') return 'pdf';
        return 'document';
    }
}
