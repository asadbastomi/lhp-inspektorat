<?php

namespace App\Livewire;

use App\Models\Lhp;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LhpManager extends Component
{
    use WithPagination;

    public $lhp_id, $tanggal_lhp, $nomor_lhp, $judul_lhp, $nomor_surat_tugas, $tanggal_penugasan, $lama_penugasan, $user_id;
    public $uploadingLhpId, $uploadingLhpNumber;
    public $isModalOpen = false;
    public $search = '';
    public $sortField = 'tanggal_lhp';
    public $sortDirection = 'desc';

    protected $listeners = ['fileUploaded' => 'handleFileUploaded'];

    protected function rules()
    {
        return [
            'tanggal_lhp' => 'required|date',
            'nomor_lhp' => 'required|string|max:100|unique:lhps,nomor_lhp,' . $this->lhp_id,
            'judul_lhp' => 'required|string',
            'nomor_surat_tugas' => 'required|string|max:100',
            'tanggal_penugasan' => 'required|date',
            'lama_penugasan' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ];
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
        $user = Auth::user();
        
        // Build query based on user role
        $query = Lhp::with('user');
        
        if ($user->role === 'irban') {
            $query->where('user_id', $user->id);
        }
        
        // Add search conditions
        $query->where(function ($q) {
            $q->where('judul_lhp', 'like', '%' . $this->search . '%')
              ->orWhere('nomor_lhp', 'like', '%' . $this->search . '%');
        });
        
        // Apply sorting and pagination
        $lhps = $query->orderBy($this->sortField, $this->sortDirection)
                      ->paginate(10);

        $irbans = User::where('role', 'irban')->get();

        return view('livewire.lhp-manager', [
            'lhps' => $lhps,
            'irbans' => $irbans,
            'user' => $user,
        ])->layout('components.layouts.admin');
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
    }

    private function resetInputFields()
    {
        $this->lhp_id = null;
        $this->tanggal_lhp = '';
        $this->nomor_lhp = '';
        $this->judul_lhp = '';
        $this->nomor_surat_tugas = '';
        $this->tanggal_penugasan = '';
        $this->lama_penugasan = '';
        $this->user_id = '';
    }

    public function store()
    {
        $this->validate();

        Lhp::updateOrCreate(['id' => $this->lhp_id], [
            'tanggal_lhp' => $this->tanggal_lhp,
            'nomor_lhp' => $this->nomor_lhp,
            'judul_lhp' => $this->judul_lhp,
            'nomor_surat_tugas' => $this->nomor_surat_tugas,
            'tanggal_penugasan' => $this->tanggal_penugasan,
            'lama_penugasan' => $this->lama_penugasan,
            'user_id' => $this->user_id,
        ]);

        $isUpdate = $this->lhp_id ? true : false;
        $this->dispatch('notify', 
            type: 'success',
            title: $isUpdate ? 'Berhasil Memperbarui Data' : 'Berhasil Menambahkan Data',
            message: $isUpdate ? 'LHP berhasil diperbarui.' : 'LHP baru berhasil ditambahkan.',
            timer: 3000
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $lhp = Lhp::findOrFail($id);
        $this->lhp_id = $id;
        $this->tanggal_lhp = $lhp->tanggal_lhp->format('Y-m-d');
        $this->nomor_lhp = $lhp->nomor_lhp;
        $this->judul_lhp = $lhp->judul_lhp;
        $this->nomor_surat_tugas = $lhp->nomor_surat_tugas;
        $this->tanggal_penugasan = $lhp->tanggal_penugasan->format('Y-m-d');
        $this->lama_penugasan = $lhp->lama_penugasan;
        $this->user_id = $lhp->user_id;

        $this->openModal();
    }

    public function delete($id)
    {
        $lhp = Lhp::findOrFail($id);
        
        // Delete associated file if exists
        if ($lhp->file_lhp) {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($lhp->file_lhp)) {
                Storage::disk('public')->delete($lhp->file_lhp);
            }
        }
        
        $lhp->delete();
        $this->dispatch('notify', 
            type: 'success',
            title: 'Berhasil Menghapus Data',
            message: 'LHP berhasil dihapus.',
            timer: 3000
        );
    }

    public function prepareUpload($lhpId)
    {
        $lhp = Lhp::findOrFail($lhpId);
        $this->uploadingLhpId = $lhp->id;
        $this->uploadingLhpNumber = $lhp->nomor_lhp;
        
        // Use dispatch for Livewire 3.x or emit for Livewire 2.x
        $this->dispatch('openUploadModal', [
            'lhpId' => $lhp->id,
            'lhpNumber' => $lhp->nomor_lhp
        ]);
        
        // Alternative for Livewire 2.x:
        // $this->emit('openUploadModal', [
        //     'lhpId' => $lhp->id,
        //     'lhpNumber' => $lhp->nomor_lhp
        // ]);
    }

    public function handleFileUploaded($data)
    {
        // Handle the uploaded file from Resumable.js
        if (!isset($data['lhpId']) || !isset($data['filePath'])) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Error',
                message: 'Data upload tidak valid.',
                timer: 5000
            );
            return;
        }

        $lhp = Lhp::find($data['lhpId']);
        
        if (!$lhp) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Data Tidak Ditemukan',
                message: 'LHP tidak ditemukan.',
                timer: 5000
            );
            return;
        }

        // Check if user has permission to upload for this LHP
        $user = Auth::user();
        if ($user->role === 'irban' && $lhp->user_id !== $user->id) {
            $this->dispatch('notify',
                type: 'error',
                title: 'Akses Ditolak',
                message: 'Anda tidak memiliki izin untuk mengunggah file untuk LHP ini.',
                timer: 5000
            );
            return;
        }

        try {
            // Delete old file if exists
            if ($lhp->file_lhp && Storage::disk('public')->exists($lhp->file_lhp)) {
                Storage::disk('public')->delete($lhp->file_lhp);
            }
            
            // Update LHP with new file path
            $lhp->update([
                'file_lhp' => $data['filePath']
            ]);
            
            $this->dispatch('notify',
                type: 'success',
                title: 'Berhasil',
                message: 'File LHP berhasil diunggah.',
                timer: 3000
            );
            
            // Reset upload-related properties
            $this->reset(['uploadingLhpId', 'uploadingLhpNumber']);
            
        } catch (\Exception $e) {
            \Log::error('Error updating LHP file: ' . $e->getMessage());
            $this->dispatch('notify',
                type: 'error',
                title: 'Gagal',
                message: 'Gagal menyimpan file LHP.',
                timer: 5000
            );
        }
    }

    public function downloadFile($id)
    {
        $lhp = Lhp::findOrFail($id);
        
        if (!$lhp->file_lhp) {
            $this->dispatch('notify',
                type: 'error',
                title: 'File Tidak Ditemukan',
                message: 'File tidak ditemukan.',
                timer: 5000
            );
            return;
        }

        if (!Storage::disk('public')->exists($lhp->file_lhp)) {
            $this->dispatch('notify',
                type: 'error',
                title: 'File Tidak Ditemukan',
                message: 'File tidak ditemukan di storage.',
                timer: 5000
            );
            return;
        }

        return Storage::disk('public')->download($lhp->file_lhp, 'LHP_' . $lhp->nomor_lhp . '.pdf');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}