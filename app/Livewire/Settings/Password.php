<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Password extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', PasswordRule::defaults(), 'confirmed'],
            ]);

            Auth::user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            $this->reset('current_password', 'password', 'password_confirmation');

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Password Diperbarui',
                'message' => 'Password Anda berhasil diubah.'
            ]);

            $this->dispatch('password-updated');

        } catch (ValidationException $e) {
            $errorMessage = 'Password saat ini tidak valid.';
            
            if (isset($e->errors()['password'])) {
                $errorMessage = implode(' ', $e->errors()['password']);
            }
            
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Gagal Memperbarui Password',
                'message' => $errorMessage,
                'timer' => 5000
            ]);
            
            $this->reset('current_password', 'password', 'password_confirmation');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal memperbarui password. Silakan coba lagi nanti.'
            ]);
            
            $this->reset('current_password', 'password', 'password_confirmation');
        }
    }
}
