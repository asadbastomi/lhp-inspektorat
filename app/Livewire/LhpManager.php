<?php

namespace App\Livewire;

use App\Models\Lhp;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class LhpManager extends Component
{
    use WithPagination;

    // Search, Sort, and Filter Properties
    public $search = '';
    public $sortField = 'tanggal_lhp';
    public $sortDirection = 'desc';
    public $statusFilter = '';
    public $perPage = 10;

    // Modal Properties
    public $isModalOpen = false;
    public $lhp_id;
    public $form = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'tanggal_lhp'],
        'sortDirection' => ['except' => 'desc'],
        'statusFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        $this->form = [
            'judul_lhp' => '',
            'user_id' => '',
            'nomor_lhp' => '',
            'tanggal_lhp' => '',
            'nomor_surat_tugas' => '',
            'tgl_surat_tugas' => '',
            'tgl_awal_penugasan' => '',
            'tgl_akhir_penugasan' => '',
            'status_penyelesaian' => 'belum_ditindaklanjuti',
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
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Lhp::with('user', 'temuans')
            ->when($this->search, fn($q) => $q->where('judul_lhp', 'like', '%' . $this->search . '%')->orWhere('nomor_lhp', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter, fn($q) => $q->where('status_penyelesaian', $this->statusFilter));

        if (Auth::user()->role === 'irban') {
            $query->where('user_id', Auth::user()->id);
        }

        $lhps = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
        $users = User::where('role', 'irban')->get();

        return view('livewire.lhp-manager', [
            'lhps' => $lhps,
            'users' => $users,
        ])->layout('components.layouts.app', ['title' => 'Manajemen LHP']);
    }

    // --- Modal and CRUD Methods ---
    public function create()
    {
        $this->resetForm();
        $this->lhp_id = null;
        $this->isModalOpen = true;
        $this->dispatch('open-modal');
    }

    public function edit($id)
    {
        $lhp = Lhp::findOrFail($id);
        $this->lhp_id = $id;
        $this->form = [
            'judul_lhp' => $lhp->judul_lhp,
            'user_id' => $lhp->user_id,
            'nomor_lhp' => $lhp->nomor_lhp,
            'tanggal_lhp' => $lhp->tanggal_lhp->format('Y-m-d'),
            'nomor_surat_tugas' => $lhp->nomor_surat_tugas,
            'tgl_surat_tugas' => optional($lhp->tgl_surat_tugas)->format('Y-m-d'),
            'tgl_awal_penugasan' => optional($lhp->tgl_awal_penugasan)->format('Y-m-d'),
            'tgl_akhir_penugasan' => optional($lhp->tgl_akhir_penugasan)->format('Y-m-d'),
            'status_penyelesaian' => $lhp->status_penyelesaian,
        ];
        $this->isModalOpen = true;
        $this->dispatch('open-modal');
    }

    public function store()
    {
        $validatedData = $this->validate([
            'form.judul_lhp' => 'required|string|max:255',
            'form.user_id' => 'required|exists:users,id',
            'form.nomor_lhp' => 'required|string|max:255',
            'form.tanggal_lhp' => 'required|date',
            'form.nomor_surat_tugas' => 'nullable|string|max:255',
            'form.tgl_surat_tugas' => 'nullable|date',
            'form.tgl_awal_penugasan' => 'nullable|date',
            'form.tgl_akhir_penugasan' => 'nullable|date|after_or_equal:form.tgl_awal_penugasan',
            'form.status_penyelesaian' => 'required|in:belum_ditindaklanjuti,dalam_proses,sesuai',
        ]);

        Lhp::create($validatedData['form']);

        $message = 'LHP berhasil dibuat.';
        $this->dispatch('notify', type: 'success', message: $message);

        $this->closeModal();
    }

    public function update()
    {
        $validatedData = $this->validate([
            'form.judul_lhp' => 'required|string|max:255',
            'form.user_id' => 'required|exists:users,id',
            'form.nomor_lhp' => 'required|string|max:255',
            'form.tanggal_lhp' => 'required|date',
            'form.nomor_surat_tugas' => 'nullable|string|max:255',
            'form.tgl_surat_tugas' => 'nullable|date',
            'form.tgl_awal_penugasan' => 'nullable|date',
            'form.tgl_akhir_penugasan' => 'nullable|date|after_or_equal:form.tgl_awal_penugasan',
            'form.status_penyelesaian' => 'required|in:belum_ditindaklanjuti,dalam_proses,sesuai',
        ]);

        $lhp = Lhp::findOrFail($this->lhp_id);
        $lhp->update($validatedData['form']);
        $message = 'LHP berhasil diperbarui.';
        $this->dispatch('notify', type: 'success', message: $message);

        $this->closeModal();
    }

    public function updateStatus($lhpId, $status)
    {
        if (auth()->user()->role !== 'admin') {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk mengubah status.');
            return;
        }

        $lhp = Lhp::find($lhpId);
        if ($lhp) {
            $lhp->status_penyelesaian = $status;
            $lhp->save();
            $this->dispatch('notify', type: 'success', message: 'Status LHP berhasil diperbarui.');
        }
    }

    public function delete($id)
    {
        Lhp::find($id)->delete();

        // UPDATED: Changed from session()->flash() to dispatch()
        $this->dispatch('notify', type: 'success', message: 'LHP berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->mount();
    }
}
