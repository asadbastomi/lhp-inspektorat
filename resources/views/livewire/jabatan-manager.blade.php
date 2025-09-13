<div class="space-y-8">
    <!-- Modern Hero Header -->
    <div
        class="relative bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-purple-600/50 to-transparent"></div>
        <div class="relative px-8 py-12 md:px-12 md:py-16">
            <div class="flex flex-col lg:flex-row items-start justify-between gap-8">
                <div class="flex-1 text-white">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <i class="fas fa-user-tie text-white mr-2"></i>
                        <span class="text-sm font-medium">Sistem Manajemen</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                        Manajemen
                        <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                            Jabatan
                        </span>
                    </h1>
                    <p class="text-xl text-purple-100 leading-relaxed mb-6">
                        Kelola semua data jabatan dalam organisasi dengan mudah dan efisien
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-purple-100">
                        <div class="flex items-center">
                            <i class="fas fa-users text-yellow-300 mr-3"></i>
                            <span>{{ $jabatans->total() }} Total Jabatan</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-chart-line text-yellow-300 mr-3"></i>
                            <span>Manajemen Terpusat</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="create"
                        class="inline-flex items-center px-8 py-4 bg-white text-purple-600 font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-plus mr-3"></i>
                        Tambah Jabatan Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center mb-6">
            <div
                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-search text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pencarian & Filter</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Temukan jabatan yang Anda cari dengan cepat</p>
            </div>
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama jabatan..."
                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
        </div>
    </div>

    <!-- Jabatan Cards Grid -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div
            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Jabatan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $jabatans->total() }} jabatan ditemukan</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button wire:click="sortBy('jabatan')"
                        class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-2 focus:ring-purple-500 transition-all duration-200">
                        <i class="fas fa-sort mr-2"></i>
                        Urutkan
                        @if ($sortField === 'jabatan')
                            <i
                                class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} ml-2 text-purple-500"></i>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            @forelse ($jabatans as $jabatan)
                <div
                    class="bg-gradient-to-r from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-2xl p-6 mb-4 last:mb-0 border border-gray-200 dark:border-gray-600 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <h4
                                    class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300">
                                    {{ $jabatan->jabatan }}
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-id-badge mr-1"></i>
                                    ID: {{ $jabatan->id }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button wire:click="edit('{{ $jabatan->id }}')"
                                class="p-3 bg-blue-100 text-blue-600 rounded-xl hover:bg-blue-200 hover:scale-110 focus:ring-4 focus:ring-blue-500/25 transition-all duration-200"
                                title="Edit Jabatan">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete('{{ $jabatan->id }}')"
                                wire:confirm="Anda yakin ingin menghapus jabatan '{{ $jabatan->jabatan }}'? Tindakan ini tidak dapat dibatalkan."
                                class="p-3 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 hover:scale-110 focus:ring-4 focus:ring-red-500/25 transition-all duration-200"
                                title="Hapus Jabatan">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div
                        class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-tie text-3xl text-gray-400"></i>
                    </div>
                    <h4 class="text-xl font-medium text-gray-900 dark:text-white mb-2">Belum ada jabatan</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai dengan menambahkan jabatan pertama untuk
                        organisasi Anda</p>
                    <button wire:click="create"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 focus:ring-4 focus:ring-purple-500/25 transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Jabatan Pertama
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($jabatans->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                {{ $jabatans->links() }}
            </div>
        @endif
    </div>

    <!-- Modern Modal -->
    <div x-data="{ show: @entangle('isModalOpen') }" x-show="show" @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 relative">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="show = false"
                aria-hidden="true"></div>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $jabatan_id ? 'Edit Jabatan' : 'Tambah Jabatan Baru' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $jabatan_id ? 'Perbarui informasi jabatan' : 'Buat jabatan baru dalam organisasi' }}
                        </p>
                    </div>
                    <button @click="show = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="space-y-6">
                        <div>
                            <label for="jabatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user-tie text-purple-500 mr-2"></i>
                                Nama Jabatan
                            </label>
                            <input type="text" id="jabatan" wire:model="jabatan"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                placeholder="Masukkan nama jabatan..." x-init="$watch('show', value => { if (value) { $nextTick(() => $el.focus()) } })">
                            @error('jabatan')
                                <div
                                    class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                                    <span class="text-red-600 dark:text-red-400 text-sm flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </span>
                                </div>
                            @enderror
                        </div>

                        <div
                            class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-700">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-purple-500 mt-1"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-purple-800 dark:text-purple-300">Tips:</h4>
                                    <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">
                                        Gunakan nama jabatan yang jelas dan mudah dipahami. Contoh: "Kepala Bidang",
                                        "Staff Admin", "Koordinator"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="show = false"
                            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-xl transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 disabled:opacity-50 transition-all duration-300 flex items-center justify-center min-w-[120px]">
                            <div wire:loading wire:target="store" class="flex items-center">
                                <i class="fas fa-spinner animate-spin mr-2"></i>
                                Menyimpan...
                            </div>
                            <div wire:loading.remove wire:target="store" class="flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                {{ $jabatan_id ? 'Perbarui' : 'Simpan' }}
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
