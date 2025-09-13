<?php

namespace App\Livewire;

use App\Models\ArsipBpkRi;
use App\Models\ArsipBpkp;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArsipManager extends Component
{
    use WithPagination, WithFileUploads;

    // Tab Management
    public $activeTab = 'bpk_ri';

    // Search & Sort Properties
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal Properties
    public $isModalOpen = false;
    public $arsip_id;

    // Form Properties
    public $form = [
        'file' => null,
        'keterangan' => '',
    ];

    // File Preview
    public $previewFile = null;
    public $isPreviewOpen = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'bpk_ri'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $rules = [
        'form.file' => 'required|file|max:204800|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip,rar',
        'form.keterangan' => 'required|string|max:500',
    ];

    protected $messages = [
        'form.file.required' => 'File harus dipilih.',
        'form.file.max' => 'Ukuran file maksimal 200MB.',
        'form.file.mimes' => 'Format file tidak didukung.',
        'form.keterangan.required' => 'Keterangan harus diisi.',
        'form.keterangan.max' => 'Keterangan maksimal 500 karakter.',
    ];

    public function mount()
    {
        $this->resetForm();
        // Initialize error bag to prevent undefined array key errors
        $this->resetErrorBag();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->search = '';
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFormFile()
    {
        // Clear any existing file validation errors when a new file is selected
        $this->resetErrorBag('form.file');

        // Perform real-time validation if file is selected
        if ($this->form['file']) {
            try {
                // Use the correct validation rules for the current context
                $rules = $this->rules;
                if ($this->arsip_id) {
                    $rules['form.file'] = 'nullable|file|max:204800|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip,rar';
                }
                $this->validate($rules, $this->messages, ['form.file' => 'file']);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Validation errors will be handled by Livewire automatically
            }
        }
    }

    public function render()
    {
        $bpkRiArsips = $this->getBpkRiArsips();
        $bpkpArsips = $this->getBpkpArsips();

        // Get total counts for stats
        $bpkRiTotal = ArsipBpkRi::count();
        $bpkpTotal = ArsipBpkp::count();

        return view('livewire.arsip-manager', [
            'bpkRiArsips' => $bpkRiArsips,
            'bpkpArsips' => $bpkpArsips,
            'bpkRiTotal' => $bpkRiTotal,
            'bpkpTotal' => $bpkpTotal,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Arsip Dokumen']);
    }

    private function getBpkRiArsips()
    {
        if ($this->activeTab !== 'bpk_ri') {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                12,
                1,
                ['path' => request()->url()]
            );
        }

        $query = ArsipBpkRi::query()
            ->when($this->search, function ($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            });

        return $query->orderBy($this->sortField, $this->sortDirection)->paginate(12);
    }

    private function getBpkpArsips()
    {
        if ($this->activeTab !== 'bpkp') {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                12,
                1,
                ['path' => request()->url()]
            );
        }

        $query = ArsipBpkp::query()
            ->when($this->search, function ($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            });

        return $query->orderBy($this->sortField, $this->sortDirection)->paginate(12);
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
        $this->dispatch('open-modal');
    }

    public function edit($id)
    {
        $model = $this->activeTab === 'bpk_ri' ? ArsipBpkRi::class : ArsipBpkp::class;
        $arsip = $model::findOrFail($id);

        $this->arsip_id = $id;
        $this->form = [
            'file' => null, // File cannot be pre-filled for security reasons
            'keterangan' => $arsip->keterangan,
        ];

        $this->isModalOpen = true;
        $this->dispatch('open-modal');
    }

    public function store()
    {
        // Update validation rule for file when editing
        if ($this->arsip_id) {
            $this->rules['form.file'] = 'nullable|file|max:204800|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip,rar';
        }

        $this->validate();

        $model = $this->activeTab === 'bpk_ri' ? ArsipBpkRi::class : ArsipBpkp::class;
        $directory = $this->activeTab === 'bpk_ri' ? 'arsip/bpk-ri' : 'arsip/bpkp';

        $data = [
            'keterangan' => $this->form['keterangan'],
        ];

        // Handle file upload
        if ($this->form['file']) {
            $file = $this->form['file'];
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($directory, $fileName, 'public');

            $data = array_merge($data, [
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            // Delete old file if editing
            if ($this->arsip_id) {
                $oldArsip = $model::find($this->arsip_id);
                if ($oldArsip && Storage::disk('public')->exists($oldArsip->file_path)) {
                    Storage::disk('public')->delete($oldArsip->file_path);
                }
            }
        }

        $model::updateOrCreate(['id' => $this->arsip_id], $data);

        $type = $this->activeTab === 'bpk_ri' ? 'BPK RI' : 'BPKP';
        $message = $this->arsip_id ? "Arsip {$type} berhasil diperbarui." : "Arsip {$type} berhasil ditambahkan.";
        $this->dispatch('notify', type: 'success', message: $message);

        $this->closeModal();
    }

    public function delete($id)
    {
        try {
            $model = $this->activeTab === 'bpk_ri' ? ArsipBpkRi::class : ArsipBpkp::class;
            $arsip = $model::findOrFail($id);

            // Delete file from storage
            if (Storage::disk('public')->exists($arsip->file_path)) {
                Storage::disk('public')->delete($arsip->file_path);
            }

            $arsip->delete();

            $type = $this->activeTab === 'bpk_ri' ? 'BPK RI' : 'BPKP';
            $this->dispatch('notify', type: 'success', message: "Arsip {$type} berhasil dihapus.");
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal menghapus arsip.');
        }
    }

    public function download($id)
    {
        $model = $this->activeTab === 'bpk_ri' ? ArsipBpkRi::class : ArsipBpkp::class;
        $arsip = $model::findOrFail($id);

        if (Storage::disk('public')->exists($arsip->file_path)) {
            return Storage::disk('public')->download($arsip->file_path, $arsip->file_name);
        }

        $this->dispatch('notify', type: 'error', message: 'File tidak ditemukan.');
    }

    public function preview($id)
    {
        $model = $this->activeTab === 'bpk_ri' ? ArsipBpkRi::class : ArsipBpkp::class;
        $arsip = $model::findOrFail($id);

        if (Storage::disk('public')->exists($arsip->file_path)) {
            $this->previewFile = $arsip;
            $this->isPreviewOpen = true;
        } else {
            $this->dispatch('notify', type: 'error', message: 'File tidak ditemukan.');
        }
    }

    public function closePreview()
    {
        $this->isPreviewOpen = false;
        $this->previewFile = null;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['arsip_id']);
        $this->form = [
            'file' => null,
            'keterangan' => '',
        ];
        $this->resetErrorBag();
    }
}
