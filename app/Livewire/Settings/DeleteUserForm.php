<?php

namespace App\Livewire\Settings;

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public string $password = '';

    /**
     * Show confirmation dialog before deleting the account.
     */
    public function confirmUserDeletion(): void
    {
        $this->dispatch('show-confirm-dialog', [
            'title' => 'Hapus Akun',
            'html' => 'Apakah Anda yakin ingin menghapus akun Anda? Semua data Anda akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan!',
            'confirmButtonText' => 'Ya, Hapus Akun',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonText' => 'Batal',
            'onConfirmed' => 'deleteUser'
        ]);
    }

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        try {
            $this->validate([
                'password' => ['required', 'string', 'current_password'],
            ]);

            $user = Auth::user();
            $userName = $user->name;
            
            // Log out and delete the user
            $logout($user);
            $user->delete();

            // Show success message before redirect
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Akun Dihapus',
                'message' => 'Akun Anda berhasil dihapus. Semua data telah dihapus secara permanen.',
                'onConfirmed' => 'redirectToHome',
                'timer' => 5000
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Gagal Menghapus Akun',
                'message' => 'Password yang Anda masukkan salah. Silakan coba lagi.',
                'timer' => 5000
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal menghapus akun. Silakan coba lagi nanti.',
                'timer' => 5000
            ]);
        }
    }

    public function render()
    {
        return view('livewire.settings.delete-user-form')
            ->with('deleteScript', "
                <script>
                    function redirectToHome() {
                        window.location.href = '/';
                    }
                    
                    // Listen for the show-confirm-dialog event
                    document.addEventListener('livewire:initialized', () => {
                        Livewire.on('show-confirm-dialog', (data) => {
                            Swal.fire({
                                title: data.title,
                                html: data.html,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: data.confirmButtonColor || '#3085d6',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: data.confirmButtonText,
                                cancelButtonText: data.cancelButtonText || 'Batal',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    if (data.onConfirmed) {
                                        if (data.onConfirmed === 'deleteUser') {
                                            Livewire.dispatch('delete-user-action');
                                        } else if (typeof window[data.onConfirmed] === 'function') {
                                            window[data.onConfirmed]();
                                        }
                                    }
                                }
                            });
                        });
                        
                        // Handle the delete action from Livewire
                        Livewire.on('delete-user-action', () => {
                            @this.call('deleteUser');
                        });
                    });
                </script>
            ");
    }
}
