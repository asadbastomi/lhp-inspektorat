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
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $this->middleware(['auth']);
    }

    private function getStorageDisk()
    {
        return app()->environment('local') ? 'public' : 'minio';
    }

    public function livewireUpload(Request $request)
    {
        Log::info('Upload request received', $request->except('file'));

        try {
            $request->validate([
                'file' => 'required|file|max:204800', // 200MB
                'field_name' => 'required|string',
            ]);

            $file = $request->file('file');
            $fieldName = $request->input('field_name');

            if ($fieldName === 'tindak_lanjut') {
                $request->validate([
                    'rekomendasi_id' => 'required|exists:rekomendasis,id',
                ]);
                return $this->handleTindakLanjutUpload($request, $file);
            }

            $request->validate([
                'lhp_id' => 'required|exists:lhps,id',
            ]);
            $lhpId = $request->input('lhp_id');

            // Validate the field name to prevent arbitrary updates
            $allowedFields = [
                'file_surat_tugas',
                'file_lhp',
                'file_kertas_kerja',
                'file_review_sheet',
                'file_nota_dinas',
                'file_p2hp',
            ];

            if (!in_array($fieldName, $allowedFields)) {
                throw new \Exception('Invalid field name provided.');
            }

            return $this->handleDokumenUpload($request, $file, $lhpId, $fieldName);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->first()], 422);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function handleDokumenUpload(Request $request, $file, $lhpId, $fieldName)
    {
        $request->validate(['file' => 'mimes:pdf']);
        $disk = $this->getStorageDisk();
        $lhp = Lhp::findOrFail($lhpId);

        if ($lhp->$fieldName && Storage::disk($disk)->exists($lhp->$fieldName)) {
            Storage::disk($disk)->delete($lhp->$fieldName);
        }

        $path = $file->store('lhp-documents', $disk);
        $lhp->$fieldName = $path;
        $lhp->save();

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil diunggah.']);
    }

    /**
     * FIX: This method now handles both creating and updating Tindak Lanjut records.
     */
    private function handleTindakLanjutUpload(Request $request, $file)
    {
        $rekomendasiId = $request->input('rekomendasi_id');
        $description = $request->input('description');
        $tindakLanjutId = $request->input('tindak_lanjut_id'); // Get the ID for editing

        $disk = $this->getStorageDisk();
        $path = $file->store('tindak-lanjut', $disk);

        $data = [
            'rekomendasi_id' => $rekomendasiId,
            'description' => $description,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_type' => $this->getFileTypeFromMime($file->getMimeType()),
        ];

        if ($tindakLanjutId) {
            // Update existing record
            $tindakLanjut = TindakLanjut::findOrFail($tindakLanjutId);
            // Delete old file before updating
            $disk = $this->getStorageDisk();
            if ($tindakLanjut->file_path && Storage::disk($disk)->exists($tindakLanjut->file_path)) {
                Storage::disk($disk)->delete($tindakLanjut->file_path);
            }
            $tindakLanjut->update($data);
            Log::info('Tindak Lanjut updated successfully', ['id' => $tindakLanjutId]);
        } else {
            // Create new record
            $data['id'] = Str::uuid();
            TindakLanjut::create($data);
            Log::info('Tindak Lanjut created successfully', ['rekomendasi_id' => $rekomendasiId]);
        }

        return response()->json(['success' => true, 'message' => 'Tindak Lanjut berhasil disimpan.']);
    }

    private function getFileTypeFromMime($mimeType)
    {
        if (Str::startsWith($mimeType, 'image/')) return 'image';
        if (Str::startsWith($mimeType, 'video/')) return 'video';
        if (Str::startsWith($mimeType, 'audio/')) return 'audio';
        if ($mimeType === 'application/pdf') return 'pdf';
        return 'document';
    }
}
