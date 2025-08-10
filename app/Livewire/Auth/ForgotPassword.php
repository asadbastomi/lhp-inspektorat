<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class ForgotPassword extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        try {
            $this->validate([
                'email' => ['required', 'string', 'email'],
            ]);

            $status = Password::sendResetLink($this->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                $this->dispatch('notify', [
                    'type' => 'success',
                    'title' => 'Tautan Reset Terkirim',
                    'message' => 'Jika email terdaftar, kami telah mengirimkan tautan reset password ke email Anda. Silakan periksa kotak masuk atau folder spam Anda.',
                    'timer' => 10000
                ]);
                
                // Reset the email field after successful submission
                $this->reset('email');
            } else {
                // This handles cases where the email doesn't exist but we don't want to reveal that
                $this->dispatch('notify', [
                    'type' => 'info',
                    'title' => 'Permintaan Diterima',
                    'message' => 'Jika email terdaftar, Anda akan menerima tautan reset password di email Anda.',
                    'timer' => 8000
                ]);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Validasi Gagal',
                'message' => 'Mohon masukkan alamat email yang valid.',
                'timer' => 5000
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal mengirim tautan reset password. Silakan coba lagi nanti.',
                'timer' => 5000
            ]);
        }
    }
}
