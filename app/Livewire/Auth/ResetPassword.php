<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class ResetPassword extends Component
{
    #[Locked]
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        try {
            $this->validate([
                'token' => ['required'],
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $status = Password::reset(
                $this->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) {
                    $user->forceFill([
                        'password' => Hash::make($this->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            // Handle the response status
            if ($status === Password::PASSWORD_RESET) {
                $this->dispatch('notify', [
                    'type' => 'success',
                    'title' => 'Password Berhasil Diubah',
                    'message' => 'Password Anda telah berhasil diubah. Anda akan dialihkan ke halaman login.',
                    'onConfirmed' => 'redirectToLogin',
                    'timer' => 5000
                ]);
                
                // Clear the form
                $this->reset(['password', 'password_confirmation']);
                return;
            }
            
            // Handle different error cases
            $errorMessage = 'Gagal mengatur ulang password. Silakan coba lagi.';
            
            if ($status === Password::INVALID_TOKEN) {
                $errorMessage = 'Token reset password tidak valid atau sudah kadaluarsa. Silakan minta tautan reset password baru.';
            } elseif ($status === Password::INVALID_USER) {
                $errorMessage = 'Email tidak terdaftar dalam sistem kami.';
            }
            
            $this->addError('email', __($status));
            
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Gagal Mengatur Ulang Password',
                'message' => $errorMessage,
                'timer' => 8000
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Validasi Gagal',
                'message' => 'Terdapat kesalahan pada data yang dimasukkan. Silakan periksa kembali form reset password.',
                'timer' => 5000
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Terjadi kesalahan saat mencoba mengatur ulang password. Silakan coba lagi nanti.',
                'timer' => 5000
            ]);
        }
    }
    
    /**
     * Redirect to login page after successful password reset
     */
    public function redirectToLogin()
    {
        $this->redirectRoute('login', navigate: true);
    }
}
