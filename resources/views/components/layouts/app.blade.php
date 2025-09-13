<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem Monitoring Laporan' }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased">
    <div x-data="{ sidebarOpen: false }">
        <x-sidebar />

        <div class="md:ml-72 transition-all duration-300 ease-in-out">
            <!-- Mobile Header -->
            <header
                class="sticky top-0 z-30 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 md:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <button @click="sidebarOpen = true"
                        class="p-2 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-rocket text-white text-xs"></i>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white">Inspektorat</span>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="p-2 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <i class="fas fa-user-circle text-lg"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 py-2">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::title(Auth::user()->role) }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Desktop Header (minimal) -->
            <header
                class="hidden md:block sticky top-0 z-30 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-end px-6 py-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-3 p-2 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="hidden lg:block text-left">
                                <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::title(Auth::user()->role) }}
                                </p>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform"
                                :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 py-2">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::title(Auth::user()->role) }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', data => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: data.type || 'success',
                    title: data.message || 'Berhasil!'
                });
            });
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>

</html>
