<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function render()
    {
        return view('livewire.auth.register')
            ->with('redirectScript', "
                <script>
                    function redirectToDashboard() {
                        window.location.href = '{{ route('dashboard') }}';
                    }
                </script>
            ")->with('registerStyles', "
                <style>
                    .register-form {
                        max-width: 400px;
                        margin: 0 auto;
                        padding: 2rem;
                        background: white;
                        border-radius: 0.5rem;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                    }
                    .register-form input[type='text'],
                    .register-form input[type='email'],
                    .register-form input[type='password'] {
                        width: 100%;
                        padding: 0.75rem;
                        border: 1px solid #e2e8f0;
                        border-radius: 0.375rem;
                        margin-bottom: 1rem;
                    }
                    .register-form button[type='submit'] {
                        width: 100%;
                        padding: 0.75rem;
                        background-color: #3b82f6;
                        color: white;
                        border: none;
                        border-radius: 0.375rem;
                        cursor: pointer;
                        font-weight: 500;
                    }
                    .register-form button[type='submit']:hover {
                        background-color: #2563eb;
                    }
                    .login-link {
                        text-align: center;
                        margin-top: 1rem;
                        color: #64748b;
                    }
                    .login-link a {
                        color: #3b82f6;
                        text-decoration: none;
                        font-weight: 500;
                    }
                    .login-link a:hover {
                        text-decoration: underline;
                    }
                </style>
            ");
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['role'] = 'irban'; // Default role for new users

            event(new Registered(($user = User::create($validated))));

            Auth::login($user);

            // Show success message before redirect
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Pendaftaran Berhasil!',
                'message' => 'Akun Anda berhasil dibuat. Selamat datang!',
                'onConfirmed' => 'redirectToDashboard'
            ]);

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
                'title' => 'Pendaftaran Gagal',
                'message' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'
            ]);
        }
    }
}
