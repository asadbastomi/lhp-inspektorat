<div class="space-y-8">
    <!-- Hero Header -->
    <div
        class="relative bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/50 to-transparent"></div>
        <div class="relative px-8 py-12 md:px-12 md:py-16">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="lg:w-2/3 text-white mb-8 lg:mb-0">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <i class="fas fa-file-alt text-white mr-2"></i>
                        <span class="text-sm font-medium">Sistem Manajemen</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Manajemen
                        <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                            LHP
                        </span>
                    </h1>
                    <p class="text-xl text-emerald-100 leading-relaxed">
                        Kelola semua Laporan Hasil Pemeriksaan (LHP) dengan mudah dan efisien dalam satu platform
                        terpadu.
                    </p>
                </div>
                <div class="lg:w-1/3 flex justify-center">
                    @if (auth()->user()->role == 'admin')
                        <button wire:click="create"
                            class="inline-flex items-center px-8 py-4 bg-white text-emerald-600 font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-plus mr-3"></i>
                            Tambah LHP Baru
                        </button>
                    @else
                        <div class="relative">
                            <div
                                class="w-48 h-48 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-white text-6xl"></i>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center mb-6">
            <div
                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-search text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pencarian & Filter</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Temukan LHP yang Anda cari dengan cepat</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search Input -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Cari berdasarkan judul atau nomor LHP..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <select wire:model.live="statusFilter"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <option value="">Semua Status</option>
                    <option value="belum_ditindaklanjuti">Belum Ditindaklanjuti</option>
                    <option value="dalam_proses">Dalam Proses</option>
                    <option value="sesuai">Sesuai</option>
                </select>
            </div>
        </div>
    </div>

    <!-- LHP Table -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div
            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar LHP</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $lhps->total() }} laporan ditemukan</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Per halaman:</span>
                    <select wire:model.live="perPage"
                        class="px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        @php
                            $columns = [
                                'judul_lhp' => 'Judul LHP',
                                'nomor_lhp' => 'Nomor LHP',
                                'user_id' => 'Irban',
                                'tanggal_lhp' => 'Tanggal LHP',
                                'tgl_surat_tugas' => 'Tgl Surat Tugas',
                                'tgl_awal_penugasan' => 'Tgl Awal Penugasan',
                                'tgl_akhir_penugasan' => 'Tgl Akhir Penugasan',
                                'status_penyelesaian' => 'Status',
                            ];
                        @endphp
                        @foreach ($columns as $field => $label)
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                @if ($field !== 'status_penyelesaian')
                                    <button wire:click="sortBy('{{ $field }}')"
                                        class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                                        <span>{{ $label }}</span>
                                        @if ($sortField === $field)
                                            <i
                                                class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up text-blue-500' : 'fa-sort-down text-blue-500' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </button>
                                @else
                                    {{ $label }}
                                @endif
                            </th>
                        @endforeach
                        <th
                            class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($lhps as $lhp)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-file-alt text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ Str::limit($lhp->judul_lhp, 40) }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $lhp->temuans->count() }} temuan
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-mono">
                                {{ $lhp->nomor_lhp }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mr-2">
                                        <span
                                            class="text-xs font-medium">{{ substr($lhp->user->name ?? 'N/A', 0, 1) }}</span>
                                    </div>
                                    {{ $lhp->user->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $lhp->tanggal_lhp->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $lhp->tgl_surat_tugas ? $lhp->tgl_surat_tugas->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $lhp->tgl_awal_penugasan ? $lhp->tgl_awal_penugasan->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $lhp->tgl_akhir_penugasan ? $lhp->tgl_akhir_penugasan->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'belum_ditindaklanjuti' => [
                                            'bg' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                            'text' => 'Belum Ditindaklanjuti',
                                        ],
                                        'dalam_proses' => [
                                            'bg' =>
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'text' => 'Dalam Proses',
                                        ],
                                        'sesuai' => [
                                            'bg' =>
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'text' => 'Sesuai',
                                        ],
                                    ];
                                    $config = $statusConfig[$lhp->status_penyelesaian] ?? [
                                        'bg' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'text' => 'Unknown',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config['bg'] }}">
                                    {{ $config['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('lhp.detail', $lhp->id) }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    @if (auth()->user()->role == 'admin')
                                        <button wire:click="edit('{{ $lhp->id }}')"
                                            class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button wire:click="delete('{{ $lhp->id }}')"
                                            class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada LHP
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai dengan membuat LHP pertama
                                        Anda</p>
                                    @if (auth()->user()->role == 'admin')
                                        <button wire:click="create"
                                            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah LHP Baru
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($lhps->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $lhps->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ show: @entangle('isModalOpen') }" x-show="show" @open-modal.window="show = true"
        @keydown.escape.window="show = false" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
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
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 z-50">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $lhp_id ? 'Edit LHP' : 'Tambah LHP Baru' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $lhp_id ? 'Perbarui informasi LHP' : 'Buat laporan hasil pemeriksaan baru' }}
                        </p>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit.prevent="{{ $lhp_id ? 'update' : 'store' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Judul LHP -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul LHP
                            </label>
                            <input wire:model="form.judul_lhp" type="text"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="Masukkan judul LHP">
                            @error('form.judul_lhp')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nomor LHP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nomor LHP
                            </label>
                            <input wire:model="form.nomor_lhp" type="text"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="Nomor LHP">
                            @error('form.nomor_lhp')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Irban -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Irban
                            </label>
                            <select wire:model="form.user_id"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Pilih Irban</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('form.user_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal LHP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal LHP
                            </label>
                            <input wire:model="form.tanggal_lhp" type="date"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @error('form.tanggal_lhp')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Surat Tugas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Surat Tugas
                            </label>
                            <input wire:model="form.tgl_surat_tugas" type="date"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @error('form.tgl_surat_tugas')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Awal Penugasan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Awal Penugasan
                            </label>
                            <input wire:model="form.tgl_awal_penugasan" type="date"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @error('form.tgl_awal_penugasan')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Akhir Penugasan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Akhir Penugasan
                            </label>
                            <input wire:model="form.tgl_akhir_penugasan" type="date"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @error('form.tgl_akhir_penugasan')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Penyelesaian
                            </label>
                            <select wire:model="form.status_penyelesaian"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="belum_ditindaklanjuti">Belum Ditindaklanjuti</option>
                                <option value="dalam_proses">Dalam Proses</option>
                                <option value="sesuai">Sesuai</option>
                            </select>
                            @error('form.status_penyelesaian')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <button type="button" @click="show = false"
                            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-xl transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-colors duration-200">
                            {{ $lhp_id ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
