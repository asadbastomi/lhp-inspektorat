<div class="space-y-8">
    <!-- Modern Hero Header -->
    <div
        class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-transparent"></div>
        <div class="relative px-8 py-12 md:px-12 md:py-16">
            <div class="flex flex-col lg:flex-row items-start justify-between gap-8">
                <div class="flex-1 text-white">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <i class="fas fa-users-cog text-white mr-2"></i>
                        <span class="text-sm font-medium">Sistem Manajemen</span>
                    </div>
                    <h1
                        class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-purple-100 bg-clip-text text-transparent">
                        Manajemen Pegawai
                    </h1>
                    <p class="text-xl text-purple-100 mb-6 max-w-2xl leading-relaxed">
                        Kelola data pegawai, jabatan, pangkat, dan status PLT dengan sistem terintegrasi
                    </p>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Total Pegawai</p>
                                    <p class="text-3xl font-bold text-white">{{ $pegawais->total() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users text-purple-200 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">PLT Aktif</p>
                                    <p class="text-3xl font-bold text-white">
                                        {{ $pegawais->where('is_plt', true)->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-pink-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user-clock text-pink-200 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Jabatan</p>
                                    <p class="text-3xl font-bold text-white">{{ $jabatans->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-indigo-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-sitemap text-indigo-200 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Pangkat</p>
                                    <p class="text-3xl font-bold text-white">{{ $pangkats->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-medal text-blue-200 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <button wire:click="create"
                        class="group bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white border border-white/30 hover:border-white/50 px-8 py-4 rounded-2xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-all">
                            <i class="fas fa-plus text-sm"></i>
                        </div>
                        Tambah Pegawai Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div
        class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-6">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-lg"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Cari nama, NIP, atau jabatan pegawai..."
                    class="w-full pl-12 pr-4 py-4 bg-white/80 dark:bg-gray-700/80 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all text-lg placeholder-gray-500">
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-filter"></i>
                <span>{{ $pegawais->total() }} hasil ditemukan</span>
            </div>
        </div>
    </div>

    <!-- Pegawai Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($pegawais as $pegawai)
            <div wire:key="{{ $pegawai->id }}"
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:scale-[1.02]">
                <!-- Card Header -->
                <div
                    class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 p-6 border-b border-gray-100 dark:border-gray-600">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ strtoupper(substr($pegawai->nama, 0, 2)) }}
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-lg text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $pegawai->nama }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    @if ($pegawai->is_plt)
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                                            <span
                                                class="text-xs text-orange-600 dark:text-orange-400 font-medium">PLT</span>
                                        </div>
                                    @else
                                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    @endif
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $pegawai->nip }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div
                                class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-tie text-indigo-600 dark:text-indigo-400 text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <i class="fas fa-briefcase text-gray-600 dark:text-gray-400 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Jabatan</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $pegawai->jabatan->jabatan ?? 'Tidak ada' }}</p>
                            </div>
                        </div>

                        @if ($pegawai->pangkat)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-medal text-gray-600 dark:text-gray-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pangkat</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $pegawai->pangkat->nama }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($pegawai->golongan)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-layer-group text-gray-600 dark:text-gray-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Golongan</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $pegawai->golongan->kode }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($pegawai->is_plt)
                            <div
                                class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-3 border border-orange-200 dark:border-orange-800">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-sm mt-0.5"></i>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-orange-900 dark:text-orange-200">Status PLT
                                        </p>
                                        <p class="text-xs text-orange-700 dark:text-orange-300 mt-1">
                                            {{ $pegawai->plt_start_date?->format('d M Y') }} -
                                            {{ $pegawai->plt_end_date?->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-100 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <button wire:click="edit('{{ $pegawai->id }}')"
                                class="group/btn flex items-center gap-2 px-3 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg transition-all duration-200 hover:scale-105">
                                <i class="fas fa-edit text-xs group-hover/btn:scale-110 transition-transform"></i>
                                <span class="text-sm font-medium">Edit</span>
                            </button>
                        </div>
                        <button wire:click="delete('{{ $pegawai->id }}')"
                            wire:confirm="Anda yakin ingin menghapus data pegawai ini?"
                            class="group/btn flex items-center gap-2 px-3 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-all duration-200 hover:scale-105">
                            <i class="fas fa-trash text-xs group-hover/btn:scale-110 transition-transform"></i>
                            <span class="text-sm font-medium">Hapus</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <!-- Enhanced Empty State -->
            <div class="col-span-full">
                <div
                    class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-3xl p-12 text-center border-2 border-dashed border-gray-300 dark:border-gray-600">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users-slash text-4xl text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Belum Ada Data Pegawai</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        Mulai dengan menambahkan data pegawai pertama untuk memulai pengelolaan sistem kepegawaian.
                    </p>
                    <button wire:click="create"
                        class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-4 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus"></i>
                        Tambah Pegawai Pertama
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Enhanced Pagination -->
    @if ($pegawais->hasPages())
        <div
            class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 p-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan {{ $pegawais->firstItem() }} - {{ $pegawais->lastItem() }} dari
                    {{ $pegawais->total() }} hasil
                </div>
                <div class="flex items-center gap-2">
                    {{ $pegawais->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Modern Modal -->
    <div x-data="{ show: @entangle('isModalOpen') }" x-show="show" @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        style="display: none;">

        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="show = false"
                aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-0">

                <!-- Modal Header -->
                <div
                    class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 px-8 py-6 border-b border-gray-100 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-plus text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $pegawai_id ? 'Edit' : 'Tambah' }} Data Pegawai
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $pegawai_id ? 'Perbarui informasi pegawai yang ada' : 'Tambahkan pegawai baru ke sistem' }}
                                </p>
                            </div>
                        </div>
                        <button @click="show = false"
                            class="w-10 h-10 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl flex items-center justify-center transition-colors">
                            <i class="fas fa-times text-gray-600 dark:text-gray-400"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <form wire:submit.prevent="store" class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <h4
                                class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-600 pb-2">
                                <i class="fas fa-user text-indigo-600 dark:text-indigo-400 mr-2"></i>
                                Informasi Dasar
                            </h4>

                            <!-- Nama -->
                            <div class="space-y-2">
                                <label for="nama"
                                    class="block text-sm font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-user text-indigo-600 dark:text-indigo-400 mr-2"></i>
                                    Nama Lengkap
                                </label>
                                <input type="text" id="nama" wire:model="form.nama"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all text-gray-900 dark:text-white placeholder-gray-500"
                                    placeholder="Masukkan nama lengkap">
                                @error('form.nama')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- NIP -->
                            <div class="space-y-2">
                                <label for="nip"
                                    class="block text-sm font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-id-card text-indigo-600 dark:text-indigo-400 mr-2"></i>
                                    NIP
                                </label>
                                <input type="text" id="nip" wire:model="form.nip"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all text-gray-900 dark:text-white placeholder-gray-500"
                                    placeholder="Masukkan NIP">
                                @error('form.nip')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Jabatan -->
                            <div class="space-y-2">
                                <label for="jabatan_id"
                                    class="block text-sm font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-briefcase text-indigo-600 dark:text-indigo-400 mr-2"></i>
                                    Jabatan
                                </label>
                                <select id="jabatan_id" wire:model="form.jabatan_id"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all text-gray-900 dark:text-white">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->jabatan }}</option>
                                    @endforeach
                                </select>
                                @error('form.jabatan_id')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Pangkat -->
                            <div class="space-y-2">
                                <label for="pangkat_id"
                                    class="block text-sm font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-medal text-indigo-600 dark:text-indigo-400 mr-2"></i>
                                    Pangkat
                                </label>
                                <select id="pangkat_id" wire:model="form.pangkat_id"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all text-gray-900 dark:text-white">
                                    <option value="">Pilih Pangkat</option>
                                    @foreach ($pangkats as $pangkat)
                                        <option value="{{ $pangkat->id }}">{{ $pangkat->nama }}</option>
                                    @endforeach
                                </select>
                                @error('form.pangkat_id')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Golongan -->
                            <div class="space-y-2">
                                <label for="golongan_id"
                                    class="block text-sm font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-layer-group text-indigo-600 dark:text-indigo-400 mr-2"></i>
                                    Golongan
                                </label>
                                <select id="golongan_id" wire:model="form.golongan_id"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all text-gray-900 dark:text-white">
                                    <option value="">Pilih Golongan</option>
                                    @foreach ($golongans as $golongan)
                                        <option value="{{ $golongan->id }}">{{ $golongan->kode }}</option>
                                    @endforeach
                                </select>
                                @error('form.golongan_id')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <h4
                                class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-600 pb-2">
                                <i class="fas fa-clock text-orange-600 dark:text-orange-400 mr-2"></i>
                                Status PLT (Opsional)
                            </h4>

                            <!-- Is PLT Toggle -->
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model.live="form.is_plt"
                                        class="w-5 h-5 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Status PLT
                                            (Pelaksana Tugas)</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Centang jika pegawai sedang
                                            menjalankan tugas PLT</p>
                                    </div>
                                </label>
                            </div>

                            @if ($form['is_plt'])
                                <!-- PLT Start Date -->
                                <div class="space-y-2">
                                    <label for="plt_start_date"
                                        class="block text-sm font-semibold text-gray-900 dark:text-white">
                                        <i class="fas fa-calendar-alt text-orange-600 dark:text-orange-400 mr-2"></i>
                                        Tanggal Mulai PLT
                                    </label>
                                    <input type="date" id="plt_start_date" wire:model="form.plt_start_date"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 dark:focus:border-orange-400 transition-all text-gray-900 dark:text-white">
                                    @error('form.plt_start_date')
                                        <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- PLT End Date -->
                                <div class="space-y-2">
                                    <label for="plt_end_date"
                                        class="block text-sm font-semibold text-gray-900 dark:text-white">
                                        <i class="fas fa-calendar-check text-orange-600 dark:text-orange-400 mr-2"></i>
                                        Tanggal Berakhir PLT
                                    </label>
                                    <input type="date" id="plt_end_date" wire:model="form.plt_end_date"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 dark:focus:border-orange-400 transition-all text-gray-900 dark:text-white">
                                    @error('form.plt_end_date')
                                        <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- PLT SK Number -->
                                <div class="space-y-2">
                                    <label for="plt_sk_number"
                                        class="block text-sm font-semibold text-gray-900 dark:text-white">
                                        <i class="fas fa-file-contract text-orange-600 dark:text-orange-400 mr-2"></i>
                                        Nomor SK PLT
                                    </label>
                                    <textarea id="plt_sk_number" wire:model="form.plt_sk_number" rows="3"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 dark:focus:border-orange-400 transition-all text-gray-900 dark:text-white placeholder-gray-500"
                                        placeholder="Masukkan nomor SK penugasan PLT"></textarea>
                                    @error('form.plt_sk_number')
                                        <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @endif

                            @if (!$form['is_plt'])
                                <div
                                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-medium text-blue-900 dark:text-blue-200">Informasi
                                                PLT</p>
                                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                                Aktifkan status PLT jika pegawai sedang menjalankan tugas sebagai
                                                Pelaksana Tugas.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div
                        class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" @click="show = false"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-semibold transition-all duration-200 hover:scale-105">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="group px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-3 min-w-[140px] justify-center">
                            <span wire:loading.remove wire:target="store" class="flex items-center gap-2">
                                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                                {{ $pegawai_id ? 'Perbarui' : 'Simpan' }}
                            </span>
                            <span wire:loading wire:target="store" class="flex items-center gap-2">
                                <i class="fas fa-spinner animate-spin"></i>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
