<div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden md:grid md:grid-cols-2 fade-in">
    
    <!-- Kolom Formulir -->
    <div class="p-8 sm:p-12">
        <div class="flex flex-col gap-6 h-full">
            <!-- Header -->
            <div class="fade-in-up">
                <a href="#" class="text-2xl font-bold text-[#263238]">
                    <i class="fas fa-rocket text-[#1B5E20]"></i> LHP Inspektorat
                </a>
                <h1 class="text-2xl font-bold text-[#263238] mt-8">Selamat Datang Kembali</h1>
                <p class="text-gray-600 mt-1">Silakan masuk untuk melanjutkan.</p>
            </div>

            <!-- Session Status -->
            @if(session('status'))
            <div class="text-center text-green-600 fade-in-up" style="animation-delay: 100ms;">
                {{ session('status') }}
            </div>
            @endif

            <form wire:submit="login" class="flex flex-col gap-6 mt-4">
                <!-- Alamat Email -->
                <div class="fade-in-up" style="animation-delay: 200ms;">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input wire:model="email" id="email" type="email" required autofocus autocomplete="email" placeholder="email@example.com" class="form-input">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kata Sandi -->
                <div class="relative fade-in-up" style="animation-delay: 300ms;">
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-[#0277BD] hover:underline">Lupa kata sandi?</a>
                        @endif
                    </div>
                    <input wire:model="password" id="password" type="password" required autocomplete="current-password" placeholder="Kata Sandi" class="form-input">
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Ingat Saya -->
                <div class="flex items-center fade-in-up" style="animation-delay: 400ms;">
                    <input wire:model="remember" id="remember" type="checkbox" class="form-checkbox h-4 w-4 text-[#1B5E20] focus:ring-[#388E3C]">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
                </div>

                <div class="fade-in-up" style="animation-delay: 500ms;">
                    <button type="submit" class="btn-primary w-full flex justify-center">
                        <span wire:loading.remove wire:target="login">Masuk</span>
                        <span wire:loading wire:target="login">
                            <i class="fas fa-spinner animate-spin"></i>&nbsp; Memproses...
                        </span>
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="text-center text-sm text-gray-600 mt-auto fade-in-up" style="animation-delay: 600ms;">
                    <span>Belum punya akun?</span>
                    <a href="{{ route('register') }}" wire:navigate class="font-medium text-[#0277BD] hover:underline">Daftar</a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Kolom Ilustrasi -->
    <div class="hidden md:block relative">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-100 to-teal-200"></div>
        <div class="absolute inset-0 flex items-center justify-center p-12">
            <div class="fade-in" style="animation-delay: 200ms;">
                <svg class="w-full h-auto" viewBox="0 0 512 384" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="40" y="304" width="432" height="48" rx="8" fill="#D1FAE5"/>
                    <rect x="40" y="32" width="432" height="240" rx="16" fill="white"/>
                    <rect x="40" y="32" width="432" height="48" rx="8" fill="#A7F3D0"/>
                    <path d="M72 128L168 200L264 128L360 184L440 104" stroke="#10B981" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                    <rect x="328" y="208" width="112" height="64" rx="8" fill="#34D399"/>
                    <circle cx="88" cy="56" r="8" fill="#10B981"/>
                    <circle cx="120" cy="56" r="8" fill="#10B981"/>
                    <circle cx="152" cy="56" r="8" fill="#10B981"/>
                </svg>
            </div>
        </div>
    </div>
</div>
