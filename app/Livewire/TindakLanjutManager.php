<?php

namespace App\Livewire;

use App\Models\Lhp;
use App\Models\TindakLanjut;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TindakLanjutManager extends Component
{
    use WithPagination, WithFileUploads;

    public $tindakLanjutId, $lhp_id, $description;
    public $file, $file_name, $file_preview;
    public $isModalOpen = false;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $lhps;

    protected $rules = [
        'lhp_id' => 'required|exists:lhps,id',
        'file' => 'required|file|max:20480', // 20MB max
        'description' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->lhps = Lhp::orderBy('nomor_lhp')->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $query = TindakLanjut::with('lhp')
            ->when($this->search, function ($query) {
                $query->where('file_name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('lhp', function ($q) {
                        $q->where('nomor_lhp', 'like', '%' . $this->search . '%')
                          ->orWhere('judul_lhp', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.tindak-lanjut-manager', [
            'tindakLanjuts' => $query->paginate($this->perPage)
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->tindakLanjutId = null;
        $this->lhp_id = '';
        $this->file = null;
        $this->file_name = '';
        $this->file_preview = null;
        $this->description = '';
    }

    public function updatedFile()
    {
        $this->validateOnly('file');
        $this->file_name = $this->file->getClientOriginalName();
        
        // Preview for images and PDFs
        if (in_array($this->file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'])) {
            $this->file_preview = $this->file->temporaryUrl();
        } else {
            $this->file_preview = null;
        }
    }

    public function store()
    {
        $this->validate();
        
        try {
            $filePath = $this->file->store('tindak-lanjut', 'public');
            
            $data = [
                'lhp_id' => $this->lhp_id,
                'file_name' => $this->file_name,
                'file_path' => $filePath,
                'file_type' => $this->getFileType($this->file->getMimeType()),
                'mime_type' => $this->file->getMimeType(),
                'file_size' => $this->file->getSize(),
                'description' => $this->description,
            ];

            TindakLanjut::updateOrCreate(['id' => $this->tindakLanjutId], $data);

            $this->dispatch('notify',
                type: 'success',
                title: $this->tindakLanjutId ? 'Berhasil Memperbarui Data' : 'Berhasil Menambahkan Data',
                message: $this->tindakLanjutId ? 'Data Tindak Lanjut berhasil diperbarui.' : 'Data Tindak Lanjut baru berhasil ditambahkan.',
                timer: 3000
            );

            $this->closeModal();
            $this->resetInputFields();
            
        } catch (\Exception $e) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Gagal',
                message: 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                timer: 5000
            );
        }
    }

    public function edit($id)
    {
        $tindakLanjut = TindakLanjut::findOrFail($id);
        $this->tindakLanjutId = $id;
        $this->lhp_id = $tindakLanjut->lhp_id;
        $this->file_name = $tindakLanjut->file_name;
        $this->description = $tindakLanjut->description;
        $this->file_preview = $tindakLanjut->file_url;
        
        $this->openModal();
    }

    public function delete($id)
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
            
        } catch (\Exception $e) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Gagal',
                message: 'Gagal menghapus data: ' . $e->getMessage(),
                timer: 5000
            );
        }
    }

    public function download($id)
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
        }
    }
}
