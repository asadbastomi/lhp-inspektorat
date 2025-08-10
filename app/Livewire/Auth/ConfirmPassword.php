<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class ConfirmPassword extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        try {
            $this->validate([
                'password' => ['required', 'string'],
            ]);

            if (! Auth::guard('web')->validate([
                'email' => Auth::user()->email,
                'password' => $this->password,
            ])) {
                throw ValidationException::withMessages([
                    'password' => __('auth.password'),
                ]);
            }

            session(['auth.password_confirmed_at' => time()]);
            
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Password berhasil dikonfirmasi.',
                'onConfirmed' => 'redirectToDashboard',
                'timer' => 2000
            ]);
            
        } catch (ValidationException $e) {
            $this->addError('password', $e->errors()['password'][0]);
            
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Password yang Anda masukkan salah. Silakan coba lagi.',
                'timer' => 5000
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal memverifikasi password. Silakan coba lagi nanti.',
                'timer' => 5000
            ]);
        }
    }
    
    public function redirectToDashboard()
    {
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}
