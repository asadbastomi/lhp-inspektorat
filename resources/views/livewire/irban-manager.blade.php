<div class="space-y-8">
    <!-- Modern Hero Header -->
    <div
        class="relative bg-gradient-to-br from-teal-600 via-emerald-600 to-green-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-teal-600/50 to-transparent"></div>
        <div class="relative px-8 py-12 md:px-12 md:py-16">
            <div class="flex flex-col lg:flex-row items-start justify-between gap-8">
                <div class="flex-1 text-white">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <i class="fas fa-user-friends text-white mr-2"></i>
                        <span class="text-sm font-medium">Sistem Manajemen</span>
                    </div>
                    <h1
                        class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-emerald-100 bg-clip-text text-transparent">
                        Manajemen Irban
                    </h1>
                    <p class="text-xl text-emerald-100 mb-6 max-w-2xl leading-relaxed">
                        Kelola semua data Inspektur Pembantu (Irban) dengan sistem terintegrasi dan modern
                    </p>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-emerald-100 text-sm font-medium">Total Irban</p>
                                    <p class="text-3xl font-bold text-white">{{ $irbans->total() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-emerald-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users text-emerald-200 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-emerald-100 text-sm font-medium">Aktif Bulan Ini</p>
                                    <p class="text-3xl font-bold text-white">{{ $irbans->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-green-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user-check text-green-200 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-emerald-100 text-sm font-medium">Pegawai Tersedia</p>
                                    <p class="text-3xl font-bold text-white">{{ $pegawai->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-teal-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user-plus text-teal-200 text-xl"></i>
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
                        Tambah Irban Baru
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
                    placeholder="Cari nama atau email Irban..."
                    class="w-full pl-12 pr-4 py-4 bg-white/80 dark:bg-gray-700/80 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 dark:focus:border-teal-400 transition-all text-lg placeholder-gray-500">
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-filter"></i>
                <span>{{ $irbans->total() }} hasil ditemukan</span>
            </div>
        </div>
    </div>

    <!-- Irban Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($irbans as $irban)
            <div wire:key="{{ $irban->id }}"
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:scale-[1.02]">
                <!-- Card Header -->
                <div
                    class="bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 p-6 border-b border-gray-100 dark:border-gray-600">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ strtoupper(substr($irban->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-lg text-gray-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                    {{ $irban->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Inspektur Pembantu</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div
                                class="w-8 h-8 bg-teal-100 dark:bg-teal-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star text-teal-600 dark:text-teal-400 text-xs"></i>
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
                                <i class="fas fa-envelope text-gray-600 dark:text-gray-400 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                <p class="font-medium text-gray-900 dark:text-white truncate">{{ $irban->email }}</p>
                            </div>
                        </div>

                        @if ($irban->pegawai)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-id-card text-gray-600 dark:text-gray-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Jabatan</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ optional($irban->pegawai->jabatan)->jabatan ?? 'Tidak ada' }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-gray-600 dark:text-gray-400 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Bergabung</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $irban->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-100 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <button wire:click="edit('{{ $irban->id }}')"
                                class="group/btn flex items-center gap-2 px-3 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg transition-all duration-200 hover:scale-105">
                                <i class="fas fa-edit text-xs group-hover/btn:scale-110 transition-transform"></i>
                                <span class="text-sm font-medium">Edit</span>
                            </button>
                            <button wire:click="resetPassword('{{ $irban->id }}')"
                                wire:confirm="Anda yakin ingin mereset kata sandi Irban ini?"
                                class="group/btn flex items-center gap-2 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 rounded-lg transition-all duration-200 hover:scale-105">
                                <i class="fas fa-key text-xs group-hover/btn:scale-110 transition-transform"></i>
                                <span class="text-sm font-medium">Reset</span>
                            </button>
                        </div>
                        <button wire:click="delete('{{ $irban->id }}')"
                            wire:confirm="Anda yakin ingin menghapus data Irban ini?"
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
                        class="w-24 h-24 bg-gradient-to-br from-teal-100 to-emerald-100 dark:from-teal-900/30 dark:to-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users-slash text-4xl text-teal-600 dark:text-teal-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Belum Ada Data Irban</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        Mulai dengan menambahkan Inspektur Pembantu pertama Anda untuk memulai pengelolaan sistem
                        inspektorat.
                    </p>
                    <button wire:click="create"
                        class="inline-flex items-center gap-3 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-8 py-4 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus"></i>
                        Tambah Irban Pertama
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Enhanced Pagination -->
    @if ($irbans->hasPages())
        <div
            class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 p-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan {{ $irbans->firstItem() }} - {{ $irbans->lastItem() }} dari {{ $irbans->total() }}
                    hasil
                </div>
                <div class="flex items-center gap-2">
                    {{ $irbans->links() }}
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
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-0">

                <!-- Modal Header -->
                <div
                    class="bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 px-8 py-6 border-b border-gray-100 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-friends text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $irban_id ? 'Edit' : 'Tambah' }} Data Irban
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $irban_id ? 'Perbarui informasi Irban yang ada' : 'Tambahkan Inspektur Pembantu baru' }}
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
                    <div class="space-y-6">
                        <!-- Pegawai Selection -->
                        <div class="space-y-2">
                            <label for="pegawai_id" class="block text-sm font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-user text-teal-600 dark:text-teal-400 mr-2"></i>
                                Nama Pegawai
                            </label>
                            <select id="pegawai_id" wire:model="pegawai_id"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 dark:focus:border-teal-400 transition-all text-gray-900 dark:text-white">
                                <option value="">Pilih Pegawai</option>
                                @if ($irban_id)
                                    @php
                                        $currentIrbanUser = \App\Models\User::find($irban_id);
                                        if ($currentIrbanUser && $currentIrbanUser->pegawai) {
                                            $pegawai = $pegawai->prepend($currentIrbanUser->pegawai);
                                        }
                                    @endphp
                                @endif
                                @foreach ($pegawai as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }} -
                                        {{ optional($p->jabatan)->jabatan }}</option>
                                @endforeach
                            </select>
                            @error('pegawai_id')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-envelope text-teal-600 dark:text-teal-400 mr-2"></i>
                                Email
                            </label>
                            <input type="email" id="email" wire:model="email"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 dark:focus:border-teal-400 transition-all text-gray-900 dark:text-white placeholder-gray-500"
                                placeholder="contoh@email.com">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="password"
                                    class="block text-sm font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-lock text-teal-600 dark:text-teal-400 mr-2"></i>
                                    Password
                                </label>
                                <input type="password" id="password" wire:model="password"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 dark:focus:border-teal-400 transition-all text-gray-900 dark:text-white placeholder-gray-500"
                                    placeholder="{{ $irban_id ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="password_confirmation"
                                    class="block text-sm font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-lock text-teal-600 dark:text-teal-400 mr-2"></i>
                                    Konfirmasi Password
                                </label>
                                <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 dark:focus:border-teal-400 transition-all text-gray-900 dark:text-white placeholder-gray-500"
                                    placeholder="Ulangi password">
                            </div>
                        </div>

                        @if ($irban_id)
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900 dark:text-blue-200">Informasi
                                            Password</p>
                                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                            Kosongkan field password jika tidak ingin mengubah password yang ada.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Actions -->
                    <div
                        class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" @click="show = false"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-semibold transition-all duration-200 hover:scale-105">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="group px-8 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-3 min-w-[140px] justify-center">
                            <span wire:loading.remove wire:target="store" class="flex items-center gap-2">
                                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                                {{ $irban_id ? 'Perbarui' : 'Simpan' }}
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
