<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem Monitoring Laporan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @livewireStyles
    @stack('styles')

    <style>
        /* Your Full app.css Configuration */
        :root {
            --color-primary: #a8d8ea; /* Light Blue */
            --color-secondary: #f8b195; /* Peach */
            --color-accent: #f67280;   /* Coral Pink */
            --color-light: #f8f9fa;
            --color-dark: #2c3e50;     /* Dark Slate Blue */
        }

        @layer base {
            * {
                transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 200ms;
            }
            
            body {
                background: linear-gradient(to bottom right, #eff6ff 0%, #fdf2f8 100%);
                min-height: 100vh;
                font-family: 'Inter', sans-serif;
                color: var(--color-dark);
            }
            
            ::-webkit-scrollbar { width: 0.5rem; }
            ::-webkit-scrollbar-track { background-color: transparent; }
            ::-webkit-scrollbar-thumb { background-color: #bfdbfe; border-radius: 9999px; }
            ::-webkit-scrollbar-thumb:hover { background-color: #93c5fd; }
        }

        @layer components {
            .btn {
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                transform: translateZ(0);
                border-radius: 0.75rem;
                border: none;
                cursor: pointer;
            }
            
            .btn:hover { transform: scale(1.03) translateZ(0); }
            .btn:active { transform: scale(0.97) translateZ(0); }
            
            .btn-primary {
                background-color: var(--color-accent);
                color: white;
                box-shadow: 0 4px 14px rgba(246, 114, 128, 0.3);
            }
            .btn-primary:hover {
                background-color: #f45b6d; /* Darker accent */
                box-shadow: 0 6px 20px rgba(246, 114, 128, 0.4);
            }
            
            .card {
                background-color: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border-radius: 1.25rem;
                padding: 2rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.6);
            }
        }


        /* --- UPDATED: Global Button Styles --- */
        .btn-primary {
            background-color: #1B5E20; /* Emerald Green */
            color: white;
            border-radius: 0.75rem; /* 12px */
            font-weight: 600;
            padding: 0.75rem 1.5rem; /* 12px 24px */
            transition: all 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #388E3C; /* Moss Green */
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.2);
            transform: scale(1.05);
        }
        .btn-secondary {
            background-color: #e2e8f0; /* gray-200 */
            color: #475569; /* gray-600 */
            border-radius: 0.75rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease-in-out;
        }
        .btn-secondary:hover {
            background-color: #cbd5e1; /* gray-300 */
        }
    </style>
</head>
<body class="antialiased">
    <header class="sticky top-0 z-40">
        <nav class="container mx-auto my-4 px-6 py-3 card flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-[--color-dark]">
                <i class="fas fa-rocket text-[--color-accent]"></i> SIMONILA
            </a>
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-[--color-dark]">
                <a href="{{ route('dashboard') }}" class="hover:text-[--color-accent] transition {{ request()->routeIs('dashboard') ? 'text-[--color-accent] font-semibold' : '' }}">Dashboard</a>
                <a href="{{ route('lhps') }}" class="hover:text-[--color-accent] transition {{ request()->routeIs('lhps*') ? 'text-[--color-accent] font-semibold' : '' }}">LHP</a>
                <a href="{{ route('irbans') }}" class="hover:text-[--color-accent] transition {{ request()->routeIs('irbans*') ? 'text-[--color-accent] font-semibold' : '' }}">Irban</a>
                <a href="#" class="hover:text-[--color-accent] transition">Users</a>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 text-[--color-dark]">
                    <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="relative z-10">
        {{ $slot }}
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Global notification handler -->
    <script>
        // Handle Livewire flash messages
        document.addEventListener('livewire:initialized', () => {
            
            
            // Listen for Livewire flash messages
            Livewire.on('notify', (data) => {
                
                
                // Handle both direct object and array-wrapped data
                let notificationData = {};
                
                if (Array.isArray(data) && data.length > 0) {
                    // If data is an array, use the first element
                    notificationData = data[0] || {};
                } else if (typeof data === 'object' && data !== null) {
                    // If data is an object, use it directly
                    notificationData = data;
                }
                
                
                
                // Extract values with defaults
                const type = notificationData.type || 'info';
                const title = notificationData.title || 'Notification';
                const message = notificationData.message || '';
                const timer = notificationData.timer || 3000;
                
                
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: timer,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                

                // For toast notifications, combine title and message for better display
                const toastTitle = title;
                const toastHtml = message ? `<div class="text-sm mt-1">${message}</div>` : '';
                
                
                const toast = Toast.fire({
                    icon: type,
                    title: toastTitle,
                    html: toastHtml
                });
                
                // Debug the toast instance
                
                toast.then((result) => {
                    
                }).catch((error) => {
                    console.error('Toast error:', error);
                });
            });
        });

        // Handle session flash messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        @endif

        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: '{{ session('info') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error Validasi',
                    text: '{{ $error }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endforeach
        @endif
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>