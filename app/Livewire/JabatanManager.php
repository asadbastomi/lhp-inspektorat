<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Jabatan;

class JabatanManager extends Component
{
    use WithPagination;

    // Search & Sort Properties
    public $search = '';
    public $sortField = 'jabatan';
    public $sortDirection = 'asc';

    // Modal Properties
    public $isModalOpen = false;
    public $jabatan_id;
    public $jabatan;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'jabatan'],
        'sortDirection' => ['except' => 'asc'],
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Jabatan::when($this->search, function ($q) {
            $q->where('jabatan', 'like', '%' . $this->search . '%');
        });

        $jabatans = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.jabatan-manager', [
            'jabatans' => $jabatans,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Irban']);
    }

    // --- Modal and CRUD Methods ---

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $this->jabatan_id = $id;
        $this->jabatan = $jabatan->jabatan;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $rules = [
            'jabatan' => 'required|unique:jabatans,jabatan,' . $this->jabatan_id,
        ];

        $validatedData = $this->validate($rules);

        $userData = [
            'jabatan' => $validatedData['jabatan'],
        ];

        Jabatan::updateOrCreate(['id' => $this->jabatan_id], $userData);

        $this->dispatch('notify', type: 'success', message: $this->jabatan_id ? 'Data Jabatan berhasil diperbarui.' : 'Jabatan baru berhasil ditambahkan.');
        $this->closeModal();
    }

    public function delete($id)
    {
        Jabatan::find($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Data Jabatan berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['jabatan_id', 'jabatan']);
        $this->resetErrorBag();
    }
}
