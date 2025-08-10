<?php

namespace App\Livewire\Auth;

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class VerifyEmail extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        try {
            $user = Auth::user();
            
            if ($user->hasVerifiedEmail()) {
                $this->dispatch('notify', [
                    'type' => 'info',
                    'title' => 'Email Sudah Terverifikasi',
                    'message' => 'Email Anda sudah terverifikasi. Mengalihkan ke dashboard...',
                    'onConfirmed' => 'redirectToDashboard',
                    'timer' => 2000
                ]);
                return;
            }

            $user->sendEmailVerificationNotification();

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Tautan Verifikasi Terkirim',
                'message' => 'Tautan verifikasi baru telah dikirim ke alamat email Anda. Silakan periksa kotak masuk atau folder spam Anda.',
                'timer' => 8000
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal mengirim tautan verifikasi. Silakan coba lagi nanti.',
                'timer' => 5000
            ]);
        }
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        try {
            $logout();
            
            $this->dispatch('notify', [
                'type' => 'info',
                'title' => 'Berhasil Keluar',
                'message' => 'Anda telah berhasil keluar dari akun Anda.',
                'onConfirmed' => 'redirectToHome',
                'timer' => 3000
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal untuk keluar. Silakan coba lagi.',
                'timer' => 5000
            ]);
        }
    }
        
    /**
     * Redirect to dashboard after email is already verified
     */
    public function redirectToDashboard()
    {
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
    
    /**
     * Redirect to home page after logout
     */
    public function redirectToHome()
    {
        $this->redirect('/', navigate: true);
    }
}
