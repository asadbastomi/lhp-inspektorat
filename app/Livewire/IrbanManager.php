<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Pegawai; // Assuming you have a Pegawai model
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class IrbanManager extends Component
{
    use WithPagination;

    // Search & Sort Properties
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Modal Properties
    public $isModalOpen = false;
    public $irban_id;
    public $name, $email, $password, $password_confirmation;
    public $pegawai_id;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
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
        $query = User::where('role', 'irban')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        $irbans = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        // Fetch Pegawai for the modal dropdown, excluding those already assigned as Irban
        $existingIrbanPegawaiIds = User::where('role', 'irban')->pluck('pegawai_id')->filter();
        $pegawai = Pegawai::whereNotIn('id', $existingIrbanPegawaiIds)->get();

        return view('livewire.irban-manager', [
            'irbans' => $irbans,
            'pegawai' => $pegawai
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
        $irban = User::findOrFail($id);
        $this->irban_id = $id;
        $this->name = $irban->name;
        $this->email = $irban->email;
        $this->pegawai_id = $irban->pegawai_id;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $rules = [
            'email' => 'required|email|unique:users,email,' . $this->irban_id,
            'pegawai_id' => 'required|exists:pegawais,id',
        ];

        // Password is only required when creating a new user
        if (!$this->irban_id) {
            $rules['password'] = 'required|min:8|confirmed';
        } elseif ($this->password) {
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        $validatedData = $this->validate($rules);

        $pegawai = Pegawai::find($validatedData['pegawai_id']);

        $userData = [
            'name' => $pegawai->nama,
            'email' => $validatedData['email'],
            'pegawai_id' => $validatedData['pegawai_id'],
            'role' => 'irban',
        ];

        if (!empty($validatedData['password'])) {
            $userData['password'] = Hash::make($validatedData['password']);
        }

        User::updateOrCreate(['id' => $this->irban_id], $userData);

        $this->dispatch('notify', type: 'success', message: $this->irban_id ? 'Data Irban berhasil diperbarui.' : 'Irban baru berhasil ditambahkan.');
        $this->closeModal();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Data Irban berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $irban = User::findOrFail($id);
        $irban->password = Hash::make('12345678');
        $irban->save();
        $this->dispatch('notify', type: 'success', message: 'Kata sandi Irban berhasil direset.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['irban_id', 'name', 'email', 'password', 'password_confirmation', 'pegawai_id']);
        $this->resetErrorBag();
    }
}
