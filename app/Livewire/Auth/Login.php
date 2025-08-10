<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function render()
    {
        return view('livewire.auth.login')
            ->with('redirectScript', "
                <script>
                    function redirectToDashboard() {
                        window.location.href = '{{ route('dashboard') }}';
                    }
                </script>
            ")->with('loginScript', "
                <script>
                    document.addEventListener('livewire:initialized', () => {
                        Livewire.on('redirectToDashboard', () => {
                            setTimeout(() => {
                                window.location.href = '{{ route('dashboard') }}';
                            }, 1000);
                        });
                    });
                </script>
            ")->with('loginStyles', "
                <style>
                    .login-form {
                        max-width: 400px;
                        margin: 0 auto;
                        padding: 2rem;
                        background: white;
                        border-radius: 0.5rem;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                    }
                    .login-form input[type='email'],
                    .login-form input[type='password'] {
                        width: 100%;
                        padding: 0.75rem;
                        border: 1px solid #e2e8f0;
                        border-radius: 0.375rem;
                        margin-bottom: 1rem;
                    }
                    .login-form button[type='submit'] {
                        width: 100%;
                        padding: 0.75rem;
                        background-color: #3b82f6;
                        color: white;
                        border: none;
                        border-radius: 0.375rem;
                        cursor: pointer;
                        font-weight: 500;
                    }
                    .login-form button[type='submit']:hover {
                        background-color: #2563eb;
                    }
                </style>
            ");
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        try {
            $this->validate();
            $this->ensureIsNotRateLimited();

            if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                RateLimiter::hit($this->throttleKey());

                $this->dispatch('notify', [
                    'type' => 'error',
                    'title' => 'Login Gagal',
                    'message' => 'Email atau password salah.'
                ]);
                
                return;
            }

            RateLimiter::clear($this->throttleKey());
            Session::regenerate();
            
            // Show success message before redirect
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Login Berhasil',
                'message' => 'Selamat datang kembali!',
                'onConfirmed' => 'redirectToDashboard'
            ]);
            
        } catch (ValidationException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Validasi Gagal',
                'message' => implode(' ', $e->errors()['email'] ?? ['Terjadi kesalahan validasi.'])
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Terjadi kesalahan saat proses login.'
            ]);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $minutes = ceil($seconds / 60);
        
        $this->dispatch('notify', [
            'type' => 'error',
            'title' => 'Terlalu Banyak Percobaan',
            'message' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit.",
            'timer' => 10000 // Show for 10 seconds
        ]);
        
        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => $minutes,
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
