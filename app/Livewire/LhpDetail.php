<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Lhp;
use Illuminate\Support\Facades\Storage;

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
    
    // Upload progress tracking
    public $uploadProgress = [];
    public $timeRemaining = [];
    
    public $message = '';

    public function mount($id)
    {
        $this->lhpId = $id;
        $this->lhp = Lhp::findOrFail($id);
        
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
        try {
            $this->validate([
                'temuan' => 'nullable|string',
                'rincian_rekomendasi' => 'nullable|string',
                'besaran_temuan' => 'nullable|string',
                'tindak_lanjut' => 'nullable|string',
            ]);
            
            // Update the LHP record
            $this->lhp->update([
                'temuan' => $this->temuan,
                'rincian_rekomendasi' => $this->rincian_rekomendasi,
                'besaran_temuan' => $this->besaran_temuan,
                'tindak_lanjut' => $this->tindak_lanjut,
            ]);
            
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Data berhasil disimpan'
            ]);
            
            // Refresh the component
            $this->refresh();
            
        } catch (\Exception $e) {
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