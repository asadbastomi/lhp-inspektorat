<?php

namespace App\Livewire;

use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\Pangkat;
use App\Models\Golongan;
use Livewire\Component;
use Livewire\WithPagination;

class PegawaiManager extends Component
{
    use WithPagination;

    // Search & Sort Properties
    public $search = '';
    public $sortField = 'nama';
    public $sortDirection = 'asc';

    // Modal Properties
    public $isModalOpen = false;
    public $pegawai_id;

    // Form Properties
    public $form = [
        'nama' => '',
        'nip' => '',
        'jabatan_id' => '',
        'pangkat_id' => '',
        'golongan_id' => '',
        'is_plt' => false,
        'plt_start_date' => '',
        'plt_end_date' => '',
        'plt_sk_number' => '',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'nama'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $rules = [
        'form.nama' => 'required|string|max:255',
        'form.nip' => 'required|string|max:255|unique:pegawais,nip',
        'form.jabatan_id' => 'required|exists:jabatans,id',
        'form.pangkat_id' => 'nullable|exists:pangkats,id',
        'form.golongan_id' => 'nullable|exists:golongans,id',
        'form.is_plt' => 'boolean',
        'form.plt_start_date' => 'nullable|date|required_if:form.is_plt,true',
        'form.plt_end_date' => 'nullable|date|after:form.plt_start_date|required_if:form.is_plt,true',
        'form.plt_sk_number' => 'nullable|string|required_if:form.is_plt,true',
    ];

    protected $messages = [
        'form.nama.required' => 'Nama pegawai harus diisi.',
        'form.nip.required' => 'NIP harus diisi.',
        'form.nip.unique' => 'NIP sudah terdaftar.',
        'form.jabatan_id.required' => 'Jabatan harus dipilih.',
        'form.plt_start_date.required_if' => 'Tanggal mulai PLT harus diisi.',
        'form.plt_end_date.required_if' => 'Tanggal berakhir PLT harus diisi.',
        'form.plt_end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
        'form.plt_sk_number.required_if' => 'Nomor SK PLT harus diisi.',
    ];

    public function mount()
    {
        $this->form = [
            'nama' => '',
            'nip' => '',
            'jabatan_id' => '',
            'pangkat_id' => '',
            'golongan_id' => '',
            'is_plt' => false,
            'plt_start_date' => '',
            'plt_end_date' => '',
            'plt_sk_number' => '',
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Pegawai::with(['jabatan', 'pangkat', 'golongan'])
            ->when($this->search, function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nip', 'like', '%' . $this->search . '%')
                    ->orWhereHas('jabatan', function ($jabatanQuery) {
                        $jabatanQuery->where('jabatan', 'like', '%' . $this->search . '%');
                    });
            });

        $pegawais = $query->orderBy($this->sortField, $this->sortDirection)->paginate(12);

        // Get dropdown data
        $jabatans = Jabatan::orderBy('jabatan')->get();
        $pangkats = Pangkat::orderBy('nama')->get();
        $golongans = Golongan::orderBy('kode')->get();

        return view('livewire.pegawai-manager', [
            'pegawais' => $pegawais,
            'jabatans' => $jabatans,
            'pangkats' => $pangkats,
            'golongans' => $golongans,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Pegawai']);
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
        $this->dispatch('open-modal');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $this->pegawai_id = $id;

        $this->form = [
            'nama' => $pegawai->nama,
            'nip' => $pegawai->nip,
            'jabatan_id' => $pegawai->jabatan_id,
            'pangkat_id' => $pegawai->pangkat_id,
            'golongan_id' => $pegawai->golongan_id,
            'is_plt' => $pegawai->is_plt,
            'plt_start_date' => $pegawai->plt_start_date?->format('Y-m-d') ?? '',
            'plt_end_date' => $pegawai->plt_end_date?->format('Y-m-d') ?? '',
            'plt_sk_number' => $pegawai->plt_sk_number ?? '',
        ];

        $this->isModalOpen = true;
        $this->dispatch('open-modal');
    }

    public function store()
    {
        // Update validation rule for NIP when editing
        if ($this->pegawai_id) {
            $this->rules['form.nip'] = 'required|string|max:255|unique:pegawais,nip,' . $this->pegawai_id;
        }

        $this->validate();

        $data = $this->form;

        // Convert date strings to Carbon instances or null
        $data['plt_start_date'] = $data['plt_start_date'] ? $data['plt_start_date'] : null;
        $data['plt_end_date'] = $data['plt_end_date'] ? $data['plt_end_date'] : null;

        // Clear PLT data if not PLT
        if (!$data['is_plt']) {
            $data['plt_start_date'] = null;
            $data['plt_end_date'] = null;
            $data['plt_sk_number'] = null;
        }

        Pegawai::updateOrCreate(['id' => $this->pegawai_id], $data);

        $message = $this->pegawai_id ? 'Data pegawai berhasil diperbarui.' : 'Pegawai baru berhasil ditambahkan.';
        $this->dispatch('notify', type: 'success', message: $message);

        $this->closeModal();
    }

    public function delete($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
            $pegawai->delete();
            $this->dispatch('notify', type: 'success', message: 'Data pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal menghapus data pegawai. Data mungkin masih digunakan.');
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['pegawai_id']);
        $this->form = [
            'nama' => '',
            'nip' => '',
            'jabatan_id' => '',
            'pangkat_id' => '',
            'golongan_id' => '',
            'is_plt' => false,
            'plt_start_date' => '',
            'plt_end_date' => '',
            'plt_sk_number' => '',
        ];
        $this->resetErrorBag();
    }
}
