<!-- Mobile Backdrop -->
<div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm md:hidden"
    @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
</div>

<!-- Sidebar -->
<aside
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out md:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
    style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">

    <!-- Header -->
    <div
        class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
        <div class="flex items-center space-x-3">
            <div
                class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-rocket text-white text-sm"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Inspektorat</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Dasbor</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
            <i class="fas fa-times text-gray-500"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 overflow-y-auto">
        @if (auth()->user()->role === 'admin')
            <!-- Main Menu Section -->
            <div class="mb-8">
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-2">Menu Utama</h2>
                <div class="space-y-2">
                    <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-tachometer-alt text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">Dasbor</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ringkasan & Analitik</p>
                            </div>
                        </div>
                    </x-sidebar-link>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-full p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-all duration-200 hover:shadow-sm text-left">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-xl flex items-center justify-center mr-3">
                                        <i class="fas fa-users text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">Manajemen Pegawai</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Kelola anggota tim</p>
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                        :class="{ 'rotate-180': open }"></i>
                                </div>
                            </div>
                        </button>

                        <div x-show="open" x-collapse.duration.300ms
                            class="mt-2 space-y-2 pl-4 border-l-2 border-gray-200 dark:border-gray-700 ml-3">
                            <x-sidebar-link href="{{ route('pegawai') }}" :active="request()->routeIs('pegawai')">
                                <div class="flex items-center pl-4">
                                    <div
                                        class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-users-cog text-gray-600 dark:text-gray-400 text-sm"></i>
                                    </div>
                                    <span>Data Pegawai</span>
                                </div>
                            </x-sidebar-link>
                            <x-sidebar-link href="{{ route('irbans') }}" :active="request()->routeIs('irbans')">
                                <div class="flex items-center pl-4">
                                    <div
                                        class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user-friends text-gray-600 dark:text-gray-400 text-sm"></i>
                                    </div>
                                    <span>Semua Irban</span>
                                </div>
                            </x-sidebar-link>
                        </div>
                    </div>

                    <x-sidebar-link href="{{ route('jabatan') }}" :active="request()->routeIs('jabatan*')">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-sitemap text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">Jabatan</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Posisi jabatan</p>
                            </div>
                        </div>
                    </x-sidebar-link>

                    <x-sidebar-link href="{{ route('lhps') }}" :active="request()->routeIs('lhps*')">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-orange-600 dark:text-orange-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">LHP</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Laporan audit</p>
                            </div>
                        </div>
                    </x-sidebar-link>

                    <x-sidebar-link href="{{ route('arsip') }}" :active="request()->routeIs('arsip*')">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-blue-200 dark:from-cyan-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-archive text-cyan-600 dark:text-cyan-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">Arsip Dokumen</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">BPK RI & BPKP</p>
                            </div>
                        </div>
                    </x-sidebar-link>
                </div>
            </div>

            <!-- Reports Section -->
            <div>
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-2">Laporan</h2>
                <div class="space-y-2">
                    <x-sidebar-link href="{{ route('reports.lhp') }}" :active="request()->routeIs('reports.lhp*')">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-pink-100 to-pink-200 dark:from-pink-900/30 dark:to-pink-800/30 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-chart-pie text-pink-600 dark:text-pink-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">Pelaporan</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Analitik & Ekspor</p>
                            </div>
                        </div>
                    </x-sidebar-link>
                </div>
            </div>
        @elseif (auth()->user()->role === 'irban')
            <!-- Irban Menu -->
            <div class="mb-8">
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-2">Menu Utama</h2>
                <div class="space-y-2">
                    <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-tachometer-alt text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">Dasbor</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ringkasan & Analitik</p>
                            </div>
                        </div>
                    </x-sidebar-link>

                    <x-sidebar-link href="{{ route('lhps') }}" :active="request()->routeIs('lhps*')">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-orange-600 dark:text-orange-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">LHP</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Laporan audit</p>
                            </div>
                        </div>
                    </x-sidebar-link>
                </div>
            </div>
        @endif
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
        <div
            class="flex items-center p-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="relative">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div
                    class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white dark:border-gray-800">
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::title(Auth::user()->role) }}</p>
            </div>
            <div class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-ellipsis-v"></i>
            </div>
        </div>
    </div>
</aside>
