<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Pegawai;

class IrbanManager extends Component
{
    use WithPagination;

    public $irban_id, $email, $password, $role, $pegawai_id;
    public $pegawai = [];
    public $isModalOpen = false;
    public $search = '';
    public $sortField = 'email';
    public $sortDirection = 'asc';

    protected function rules()
    {
        return [
            'email' => 'required|string|max:100|unique:users,email,' . $this->irban_id,
            'password' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'pegawai_id' => 'required|exists:pegawais,id',
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
        $irbans = User::whereRole('irban')->where(function ($query) {
            $query->where('email', 'like', '%' . $this->search . '%')
                  ->orWhere('pegawai_id', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(10);

        $this->pegawai = Pegawai::whereHas('jabatan', function ($query) {
            $query->where('jabatan', 'like', '%Irban Wilayah I%')
                  ->orWhere('jabatan', 'like', '%Irban Wilayah II%')
                  ->orWhere('jabatan', 'like', '%Irban Wilayah III%')
                  ->orWhere('jabatan', 'like', '%Irban Khusus%');
        })->with('jabatan')->get();

        return view('livewire.irban-manager', [
            'irbans' => $irbans,
            'pegawai' => $this->pegawai,
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
        $this->irban_id = null;
        $this->email = '';
        $this->password = '';
        $this->role = 'irban';
        $this->pegawai_id = '';
    }

    public function store()
    {
        $this->validate();
        
        // Get the pegawai's name
        $pegawai = Pegawai::findOrFail($this->pegawai_id);


        $userData = [
            'name' => $pegawai->nama,
            'email' => $this->email,
            'role' => $this->role,
            'pegawai_id' => $this->pegawai_id,
        ];

        // Only update password if it's provided
        if (!empty($this->password)) {
            $userData['password'] = bcrypt($this->password);
        }

        User::updateOrCreate(['id' => $this->irban_id], $userData);

        session()->flash('message', $this->irban_id ? 'Irban Updated Successfully.' : 'Irban Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $irban = User::findOrFail($id);
        $this->irban_id = $id;
        $this->email = $irban->email;
        $this->password = '';
        $this->role = $irban->role;
        $this->pegawai_id = $irban->pegawai_id;

        $this->openModal();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Irban Deleted Successfully.');
    }
}
