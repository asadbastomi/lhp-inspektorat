<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Lhp;
use App\Models\TindakLanjut;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LhpDetail extends Component
{
    use WithFileUploads;

    public $lhp;
    public $lhpId;
    
    // File properties
    public $file_surat_tugas;
    public $file_lhp;
    public $file_kertas_kerja;
    public $file_review_sheet;
    public $file_nota_dinas;
    
    // Other properties
    public $temuan;
    public $rincian_rekomendasi;
    public $besaran_temuan;
    public $tindak_lanjut;
    
    // Tindak Lanjut properties
    public $tindakLanjutId;
    public $tindakLanjutFile;
    public $tindakLanjutFileName;
    public $tindakLanjutDescription;
    public $tindakLanjutFilePreview;
    public $showTindakLanjutModal = false;
    
    // Upload progress tracking
    public $uploadProgress = [];
    public $timeRemaining = [];
    
    public $message = '';

    public function mount($id)
    {
        $this->lhpId = $id;
        $this->lhp = Lhp::with('tindakLanjuts')->findOrFail($id);
        
        // Set the LHP ID in session for the upload controller
        session(['current_lhp_id' => $id]);
        
        // Load existing data
        $this->temuan = $this->lhp->temuan;
        $this->rincian_rekomendasi = $this->lhp->rincian_rekomendasi;
        $this->besaran_temuan = $this->lhp->besaran_temuan;
        $this->tindak_lanjut = $this->lhp->tindak_lanjut;
        
        // Initialize upload progress
        $this->uploadProgress = [
            'file_surat_tugas' => 0,
            'file_lhp' => 0,
            'file_kertas_kerja' => 0,
            'file_review_sheet' => 0,
            'file_nota_dinas' => 0,
        ];
        
        $this->timeRemaining = [
            'file_surat_tugas' => 'Menghitung...',
            'file_lhp' => 'Menghitung...',
            'file_kertas_kerja' => 'Menghitung...',
            'file_review_sheet' => 'Menghitung...',
            'file_nota_dinas' => 'Menghitung...',
        ];
    }

    public function updateUploadProgress($fieldName, $progress, $uploaded = 0, $total = 0)
    {
        $this->uploadProgress[$fieldName] = $progress;
        
        // Calculate time remaining
        if ($progress > 0 && $progress < 100) {
            $elapsed = time() - (session('upload_start_' . $fieldName) ?: time());
            if ($elapsed > 0) {
                $rate = $uploaded / $elapsed;
                $remaining = ($total - $uploaded) / $rate;
                
                if ($remaining < 60) {
                    $this->timeRemaining[$fieldName] = round($remaining) . ' detik';
                } elseif ($remaining < 3600) {
                    $this->timeRemaining[$fieldName] = round($remaining / 60) . ' menit';
                } else {
                    $this->timeRemaining[$fieldName] = round($remaining / 3600, 1) . ' jam';
                }
            }
        } elseif ($progress >= 100) {
            $this->timeRemaining[$fieldName] = 'Selesai';
        }
        
        if ($progress == 0) {
            session(['upload_start_' . $fieldName => time()]);
        }
    }

    public function refresh()
    {
        // Reload the LHP data from database
        $this->lhp = Lhp::findOrFail($this->lhpId);
        
        // Re-set session ID
        session(['current_lhp_id' => $this->lhpId]);
        
        // Clear message after 3 seconds
        if ($this->message) {
            $this->dispatch('show-message');
        }
    }

    public function deleteFile($fieldName)
    {
        try {
            // Validate field name
            $allowedFields = ['file_surat_tugas', 'file_lhp', 'file_kertas_kerja', 'file_review_sheet', 'file_nota_dinas'];
            if (!in_array($fieldName, $allowedFields)) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Field tidak valid'
                ]);
                return;
            }
            
            // Delete file from storage
            if ($this->lhp->$fieldName && Storage::disk('public')->exists($this->lhp->$fieldName)) {
                Storage::disk('public')->delete($this->lhp->$fieldName);
            }
            
            // Update database
            $this->lhp->$fieldName = null;
            $this->lhp->save();
            
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'File berhasil dihapus'
            ]);
            
            // Refresh the component
            $this->refresh();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan saat menghapus file: ' . $e->getMessage()
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'temuan' => 'nullable|string',
            'rincian_rekomendasi' => 'nullable|string',
            'besaran_temuan' => 'nullable|numeric',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $this->lhp->update([
            'temuan' => $this->temuan,
            'rincian_rekomendasi' => $this->rincian_rekomendasi,
            'besaran_temuan' => $this->besaran_temuan,
            'tindak_lanjut' => $this->tindak_lanjut,
        ]);

        session()->flash('message', 'Data berhasil disimpan.');
        
        // Refresh the component to show updated data
        $this->lhp = Lhp::with('tindakLanjuts')->find($this->lhpId);
    }
    
    // Tindak Lanjut Methods
    public function openTindakLanjutModal($id = null)
    {
        $this->resetTindakLanjutForm();
        
        if ($id) {
            $tindakLanjut = TindakLanjut::findOrFail($id);
            $this->tindakLanjutId = $tindakLanjut->id;
            $this->tindakLanjutDescription = $tindakLanjut->description;
            $this->tindakLanjutFileName = $tindakLanjut->file_name;
            $this->tindakLanjutFilePreview = $tindakLanjut->file_url;
        }
        
        $this->showTindakLanjutModal = true;
    }
    
    public function closeTindakLanjutModal()
    {
        $this->showTindakLanjutModal = false;
        $this->resetTindakLanjutForm();
    }
    
    private function resetTindakLanjutForm()
    {
        $this->tindakLanjutId = null;
        $this->tindakLanjutFile = null;
        $this->tindakLanjutFileName = '';
        $this->tindakLanjutDescription = '';
        $this->tindakLanjutFilePreview = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }
    
    public function saveTindakLanjut()
    {
        $this->validate([
            'tindakLanjutFile' => $this->tindakLanjutId ? 'nullable|file|max:20480' : 'required|file|max:20480',
            'tindakLanjutDescription' => 'nullable|string|max:1000',
        ]);
        
        try {
            $data = [
                'lhp_id' => $this->lhpId,
                'description' => $this->tindakLanjutDescription,
            ];
            
            if ($this->tindakLanjutFile) {
                // Delete old file if exists
                if ($this->tindakLanjutId) {
                    $existing = TindakLanjut::find($this->tindakLanjutId);
                    if ($existing && Storage::disk('public')->exists($existing->file_path)) {
                        Storage::disk('public')->delete($existing->file_path);
                    }
                }
                
                // Store new file
                $filePath = $this->tindakLanjutFile->store('tindak-lanjut', 'public');
                $data = array_merge($data, [
                    'file_name' => $this->tindakLanjutFile->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $this->getFileType($this->tindakLanjutFile->getMimeType()),
                    'mime_type' => $this->tindakLanjutFile->getMimeType(),
                    'file_size' => $this->tindakLanjutFile->getSize(),
                ]);
            }
            
            TindakLanjut::updateOrCreate(
                ['id' => $this->tindakLanjutId],
                $data
            );
            
            $this->dispatch('notify',
                type: 'success',
                title: $this->tindakLanjutId ? 'Berhasil Memperbarui' : 'Berhasil Menambahkan',
                message: 'Data Tindak Lanjut berhasil ' . ($this->tindakLanjutId ? 'diperbarui' : 'ditambahkan') . '.',
                timer: 3000
            );
            
            $this->closeTindakLanjutModal();
            $this->lhp = Lhp::with('tindakLanjuts')->find($this->lhpId);
            
        } catch (\Exception $e) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Gagal Menyimpan',
                message: 'Terjadi kesalahan: ' . $e->getMessage(),
                timer: 5000
            );
        }
    }
    
    public function deleteTindakLanjut($id)
    {
        try {
            $tindakLanjut = TindakLanjut::findOrFail($id);
            
            // Delete file from storage
            if (Storage::disk('public')->exists($tindakLanjut->file_path)) {
                Storage::disk('public')->delete($tindakLanjut->file_path);
            }
            
            $tindakLanjut->delete();
            
            $this->dispatch('notify',
                type: 'success',
                title: 'Berhasil',
                message: 'Data Tindak Lanjut berhasil dihapus.',
                timer: 3000
            );
            
            $this->lhp = Lhp::with('tindakLanjuts')->find($this->lhpId);
            
        } catch (\Exception $e) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Gagal',
                message: 'Gagal menghapus data: ' . $e->getMessage(),
                timer: 5000
            );
        }
    }
    
    public function downloadTindakLanjut($id)
    {
        $tindakLanjut = TindakLanjut::findOrFail($id);
        
        if (!Storage::disk('public')->exists($tindakLanjut->file_path)) {
            $this->dispatch('notify',
                type: 'error',
                title: 'File Tidak Ditemukan',
                message: 'File tidak ditemukan di server.',
                timer: 5000
            );
            return;
        }
        
        return Storage::disk('public')->download($tindakLanjut->file_path, $tindakLanjut->file_name);
    }
    
    public function updatedTindakLanjutFile()
    {
        $this->validateOnly('tindakLanjutFile', [
            'tindakLanjutFile' => 'file|max:20480',
        ]);
        
        if ($this->tindakLanjutFile) {
            $this->tindakLanjutFileName = $this->tindakLanjutFile->getClientOriginalName();
            
            // Preview for images and PDFs
            if (in_array($this->tindakLanjutFile->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'])) {
                $this->tindakLanjutFilePreview = $this->tindakLanjutFile->temporaryUrl();
            } else {
                $this->tindakLanjutFilePreview = null;
            }
        }
    }
    
    private function getFileType($mimeType)
    {
        if (Str::startsWith($mimeType, 'image/')) {
            return 'image';
        } elseif (Str::startsWith($mimeType, 'video/')) {
            return 'video';
        } elseif (Str::startsWith($mimeType, 'audio/')) {
            return 'audio';
        } elseif ($mimeType === 'application/pdf') {
            return 'pdf';
        } elseif (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ])) {
            return 'document';
        } elseif (in_array($mimeType, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])) {
            return 'spreadsheet';
        } else {
            return 'other';
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.lhp-detail')->layout('components.layouts.admin');
    }
}