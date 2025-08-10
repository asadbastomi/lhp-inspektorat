<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        try {
            $user = Auth::user();

            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($user->id),
                ],
            ]);

            $user->fill($validated);

            $emailWasChanged = $user->isDirty('email');
            
            if ($emailWasChanged) {
                $user->email_verified_at = null;
            }

            $user->save();

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Profil Diperbarui',
                'message' => $emailWasChanged 
                    ? 'Profil berhasil diperbarui. Silakan periksa email Anda untuk verifikasi alamat email baru.'
                    : 'Profil berhasil diperbarui.'
            ]);

            $this->dispatch('profile-updated', name: $user->name);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                $errorMessages[] = implode(' ', $messages);
            }
            
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Validasi Gagal',
                'message' => implode('\n', $errorMessages),
                'timer' => 5000
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Gagal Memperbarui Profil',
                'message' => 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        try {
            $user = Auth::user();

            if ($user->hasVerifiedEmail()) {
                $this->redirectIntended(default: route('dashboard', absolute: false));
                return;
            }

            $user->sendEmailVerificationNotification();

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Email Verifikasi Dikirim',
                'message' => 'Tautan verifikasi baru telah dikirim ke alamat email Anda.'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Gagal Mengirim Email Verifikasi',
                'message' => 'Terjadi kesalahan saat mengirim email verifikasi. Silakan coba lagi nanti.'
            ]);
        }
    }
}
