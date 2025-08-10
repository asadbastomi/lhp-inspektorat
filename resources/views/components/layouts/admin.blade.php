<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Admin - Sistem Monitoring Laporan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Scripts -->
    @stack('styles')
    
    <style>
        .sidebar-active {
            transform: translateX(0);
        }
        .sidebar-hidden {
            transform: translateX(-100%);
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-50" x-data="{ sidebarOpen: false }">

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white/90 backdrop-blur-md shadow-xl transform transition-transform duration-300 ease-in-out md:translate-x-0"
         :class="sidebarOpen ? 'sidebar-active' : 'sidebar-hidden md:sidebar-active'">
        <div class="flex items-center justify-between p-6 border-b border-blue-100">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-chart-line text-blue-400 mr-2"></i>
                Dashboard
            </h2>
            <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="mt-6 px-4">
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-100' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('irbans') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('irbans') ? 'text-blue-600 bg-blue-100' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    <i class="fas fa-users mr-3"></i>
                    Irban
                </a>
                <a href="{{ route('lhps') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('lhps') ? 'text-blue-600 bg-blue-100' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    <i class="fas fa-file-alt mr-3"></i>
                    LHP
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <i class="fas fa-search mr-3"></i>
                    Temuan
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <i class="fas fa-cog mr-3"></i>
                    Pengaturan
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="md:ml-64">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-blue-100">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-800 mr-4">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard Monitoring</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-user-circle text-lg"></i>
                                <span class="hidden sm:block">{{ auth()->user()->name ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down text-sm" :class="{ 'transform rotate-180': open }"></i>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            {{ $slot }}
        </main>
    </div>

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black bg-opacity-25 md:hidden"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    @stack('scripts')
</body>
</html>
