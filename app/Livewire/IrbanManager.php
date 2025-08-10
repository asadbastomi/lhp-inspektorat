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
        try {
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

            $isUpdate = $this->irban_id ? true : false;
            
            User::updateOrCreate(['id' => $this->irban_id], $userData);

            $this->dispatch('notify', 
                type: 'success',
                title: $isUpdate ? 'Berhasil Memperbarui Data' : 'Berhasil Menambahkan Data',
                message: $isUpdate ? 'Data Irban berhasil diperbarui.' : 'Data Irban baru berhasil ditambahkan.',
                timer: 3000
            );

            $this->closeModal();
            $this->resetInputFields();
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Validasi Gagal',
                'message' => 'Terdapat kesalahan pada data yang dimasukkan. Silakan periksa kembali.',
                'timer' => 5000
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal menyimpan data. Silakan coba lagi nanti.',
                'timer' => 5000
            ]);
        }
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
        try {
            $irban = User::findOrFail($id);
            $irbanName = $irban->name;
            
            // Show confirmation dialog first
            $this->dispatch('show-confirm-dialog', [
                'title' => 'Hapus Data Irban',
                'html' => 'Apakah Anda yakin ingin menghapus data Irban <strong>' . e($irbanName) . '</strong>? Tindakan ini tidak dapat dibatalkan!',
                'confirmButtonText' => 'Ya, Hapus',
                'confirmButtonColor' => '#ef4444',
                'cancelButtonText' => 'Batal',
                'onConfirmed' => 'deleteConfirmed',
                'itemId' => $id
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal mempersiapkan penghapusan data. Silakan coba lagi nanti.',
                'timer' => 5000
            ]);
        }
    }
    
    public function deleteConfirmed($id = null)
    {
        try {
            if (!$id) {
                throw new \Exception('ID tidak valid');
            }
            
            $irban = User::findOrFail($id);
            $irbanName = $irban->name;
            
            $irban->delete();
            
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Berhasil Dihapus',
                'message' => 'Data Irban ' . e($irbanName) . ' berhasil dihapus.',
                'timer' => 3000
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Gagal Menghapus',
                'message' => 'Gagal menghapus data Irban. Silakan coba lagi nanti.',
                'timer' => 5000
            ]);
        }
    }
}
