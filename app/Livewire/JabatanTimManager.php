<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JabatanTim;

class JabatanTimManager extends Component
{
    use WithPagination;

    // Search & Sort Properties
    public $search = '';
    public $sortField = 'jabatan';
    public $sortDirection = 'asc';

    // Modal Properties
    public $isModalOpen = false;
    public $jabatan_tim_id;
    public $jabatan_tim;

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
        $query = JabatanTim::when($this->search, function ($q) {
                $q->where('jabatan', 'like', '%' . $this->search . '%');
            });

        $jabatans = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.jabatan-tim-manager', [
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
        $jabatan = JabatanTim::findOrFail($id);
        $this->jabatan_tim_id = $id;
        $this->jabatan_tim = $jabatan->jabatan;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|unique:jabatan_tims,nama,' . $this->jabatan_tim_id,
        ];

        $validatedData = $this->validate($rules);

        $userData = [
            'nama' => $validatedData['nama'],
        ];

        JabatanTim::updateOrCreate(['id' => $this->jabatan_tim_id], $userData);

        $this->dispatch('notify', ['type' => 'success', 'message' => $this->jabatan_tim_id ? 'Data Jabatan Tim berhasil diperbarui.' : 'Jabatan Tim baru berhasil ditambahkan.']);
        $this->closeModal();
    }

    public function delete($id)
    {
        JabatanTim::find($id)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Data Jabatan Tim berhasil dihapus.']);
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['jabatan_tim_id', 'jabatan_tim']);
        $this->resetErrorBag();
    }
}
