<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\Layout; // Import the Layout attribute

// FIX: Use the Layout attribute to define the layout file.
// This ensures the component is a standalone page.
// Make sure you have a layout file at: resources/views/components/layouts/auth.blade.php
#[Layout('components.layouts.app-guest')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    /**
     * Render the component's view.
     * A render method is not needed if you use the Layout attribute.
     */
    public function render()
    {
        return view('livewire.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login()
    {
        $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($this->ensureIsNotRateLimited()) {
            return; // Stop execution if rate limited
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            // Dispatch error notification for failed login
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Login Gagal',
                'message' => 'Email atau password yang Anda masukkan salah.'
            ]);
            
            return;
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();
        
        // Dispatch success notification
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'Login Berhasil',
            'message' => 'Anda akan dialihkan ke halaman dashboard...',
            'timer' => 2000
        ]);

        // Add a small delay before redirect to allow the notification to be seen
        $this->js('setTimeout(() => { window.location.href = "' . route('dashboard') . '"; }, 2000);');
    }

    /**
     * Ensure the authentication request is not rate limited.
     * Returns true if the request is rate limited.
     */
    protected function ensureIsNotRateLimited(): bool
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return false;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        
        // Dispatch error notification for rate limiting
        $this->dispatch('notify', [
            'type' => 'error',
            'message' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
            'timer' => $seconds * 1000 // Set timer to match lockout time
        ]);
        
        return true;
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
