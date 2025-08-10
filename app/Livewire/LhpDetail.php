<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lhp;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class LhpDetail extends Component
{
    use WithFileUploads;

    public $lhp;
    public $lhpId;
    
    // File uploads - Group 1
    public $file_surat_tugas;
    public $file_lhp;
    public $file_kertas_kerja;
    public $file_review_sheet;
    public $file_nota_dinas;
    
    // Findings - Group 2
    public $temuan;
    public $rincian_rekomendasi;
    public $besaran_temuan;
    public $tindak_lanjut;

    protected $rules = [
        'file_surat_tugas' => 'nullable|file|mimes:pdf|max:20480', // 20MB max
        'file_lhp' => 'nullable|file|mimes:pdf|max:204800', // 200MB max
        'file_kertas_kerja' => 'nullable|file|mimes:pdf|max:102400', // 100MB max
        'file_review_sheet' => 'nullable|file|mimes:pdf|max:51200', // 50MB max
        'file_nota_dinas' => 'nullable|file|mimes:pdf|max:51200', // 50MB max
        'temuan' => 'nullable|string',
        'rincian_rekomendasi' => 'nullable|string',
        'besaran_temuan' => 'nullable|string',
        'tindak_lanjut' => 'nullable|string',
    ];

    public function mount($id)
    {
        $this->lhpId = $id;
        $this->loadLhpData();
    }

    public function loadLhpData()
    {
        $this->lhp = Lhp::findOrFail($this->lhpId);
        
        // Initialize form fields with existing data
        $this->temuan = $this->lhp->temuan;
        $this->rincian_rekomendasi = $this->lhp->rincian_rekomendasi;
        $this->besaran_temuan = $this->lhp->besaran_temuan;
        $this->tindak_lanjut = $this->lhp->tindak_lanjut;
    }

    public function save()
    {
        $this->validate();
        
        $lhp = $this->lhp;
        
        // Handle file uploads - Group 1
        if ($this->file_surat_tugas) {
            $path = $this->file_surat_tugas->store('lhp-documents', 'public');
            $lhp->file_surat_tugas = $path;
        }
        
        if ($this->file_lhp) {
            $path = $this->file_lhp->store('lhp-documents', 'public');
            $lhp->file_lhp = $path;
        }
        
        if ($this->file_kertas_kerja) {
            $path = $this->file_kertas_kerja->store('lhp-documents', 'public');
            $lhp->file_kertas_kerja = $path;
        }
        
        if ($this->file_review_sheet) {
            $path = $this->file_review_sheet->store('lhp-documents', 'public');
            $lhp->file_review_sheet = $path;
        }
        
        if ($this->file_nota_dinas) {
            $path = $this->file_nota_dinas->store('lhp-documents', 'public');
            $lhp->file_nota_dinas = $path;
        }
        
        // Save findings - Group 2
        $lhp->temuan = $this->temuan;
        $lhp->rincian_rekomendasi = $this->rincian_rekomendasi;
        $lhp->besaran_temuan = $this->besaran_temuan;
        $lhp->tindak_lanjut = $this->tindak_lanjut;
        
        $lhp->save();
        
        session()->flash('message', 'Data berhasil disimpan.');
        $this->loadLhpData(); // Refresh the data
        
        // Reset file inputs
        $this->reset(['file_surat_tugas', 'file_lhp', 'file_kertas_kerja', 'file_review_sheet', 'file_nota_dinas']);
    }

    public function deleteFile($field)
    {
        if (in_array($field, ['file_surat_tugas', 'file_lhp', 'file_kertas_kerja', 'file_review_sheet', 'file_nota_dinas'])) {
            $lhp = $this->lhp;
            if ($lhp->$field) {
                Storage::disk('public')->delete($lhp->$field);
                $lhp->$field = null;
                $lhp->save();
                $this->loadLhpData();
                session()->flash('message', 'File berhasil dihapus.');
            }
        }
    }

    public function render()
    {
        return view('livewire.lhp-detail', [
            'lhp' => $this->lhp
        ])->layout('components.layouts.admin');
    }
}
