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

    // Modal Properties
    public $isModalOpen = false;
    public $lhp_id;
    public $judul_lhp, $user_id, $nomor_lhp, $tanggal_lhp, $nomor_surat_tugas, $tanggal_penugasan, $lama_penugasan;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'tanggal_lhp'],
        'sortDirection' => ['except' => 'desc'],
        'statusFilter' => ['except' => ''],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $query = Lhp::with('user')
            ->when($this->search, fn ($q) => $q->where('judul_lhp', 'like', '%' . $this->search . '%')->orWhere('nomor_lhp', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter, function ($q) {
                if ($this->statusFilter === 'selesai') $q->whereNotNull('file_lhp');
                elseif ($this->statusFilter === 'proses') $q->whereNull('file_lhp');
            });

            if(Auth::user()->role === 'irban') {
                $query->where('user_id', Auth::user()->id);
            }

        $lhps = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);
        $irbans = User::where('role', 'irban')->get();

        return view('livewire.lhp-manager', [
            'lhps' => $lhps,
            'irbans' => $irbans
        ])->layout('components.layouts.app', ['title' => 'Manajemen LHP']);
    }

    // --- Modal and CRUD Methods ---
    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $lhp = Lhp::findOrFail($id);
        $this->lhp_id = $id;
        $this->judul_lhp = $lhp->judul_lhp;
        $this->user_id = $lhp->user_id;
        $this->nomor_lhp = $lhp->nomor_lhp;
        $this->tanggal_lhp = $lhp->tanggal_lhp->format('Y-m-d');
        $this->nomor_surat_tugas = $lhp->nomor_surat_tugas;
        $this->tanggal_penugasan = $lhp->tanggal_penugasan->format('Y-m-d');
        $this->lama_penugasan = $lhp->lama_penugasan;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $validatedData = $this->validate([
            'judul_lhp' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'nomor_lhp' => 'required|string|max:255',
            'tanggal_lhp' => 'required|date',
            'nomor_surat_tugas' => 'required|string|max:255',
            'tanggal_penugasan' => 'required|date',
            'lama_penugasan' => 'required|integer|min:1',
        ]);

        Lhp::updateOrCreate(['id' => $this->lhp_id], $validatedData);

        // UPDATED: Changed from session()->flash() to dispatch()
        $message = $this->lhp_id ? 'LHP berhasil diperbarui.' : 'LHP berhasil dibuat.';
        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);

        $this->closeModal();
    }

    public function delete($id)
    {
        Lhp::find($id)->delete();
        
        // UPDATED: Changed from session()->flash() to dispatch()
        $this->dispatch('notify', ['type' => 'success', 'message' => 'LHP berhasil dihapus.']);
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['lhp_id', 'judul_lhp', 'user_id', 'nomor_lhp', 'tanggal_lhp', 'nomor_surat_tugas', 'tanggal_penugasan', 'lama_penugasan']);
    }
}