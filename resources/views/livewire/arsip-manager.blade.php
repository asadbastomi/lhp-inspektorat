@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="space-y-8">
    <!-- Modern Hero Header -->
    <div
        class="relative bg-gradient-to-br from-cyan-600 via-blue-600 to-indigo-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-600/50 to-transparent"></div>
        <div class="relative px-8 py-12 md:px-12 md:py-16">
            <div class="flex flex-col lg:flex-row items-start justify-between gap-8">
                <div class="flex-1 text-white">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <i class="fas fa-archive text-white mr-2"></i>
                        <span class="text-sm font-medium">Sistem Arsip Digital</span>
                    </div>
                    <h1
                        class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-cyan-100 bg-clip-text text-transparent">
                        Arsip Dokumen
                    </h1>
                    <p class="text-xl text-cyan-100 mb-6 max-w-2xl leading-relaxed">
                        Kelola arsip dokumen BPK RI dan BPKP dengan sistem penyimpanan digital yang aman dan
                        terorganisir
                    </p>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-cyan-100 text-sm font-medium">Total BPK RI</p>
                                    <p class="text-3xl font-bold text-white">{{ $bpkRiTotal }}</p>
                                </div>
                                <div class="w-12 h-12 bg-red-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-file-pdf text-red-200 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-cyan-100 text-sm font-medium">Total BPKP</p>
                                    <p class="text-3xl font-bold text-white">{{ $bpkpTotal }}</p>
                                </div>
                                <div class="w-12 h-12 bg-green-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-file-alt text-green-200 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-cyan-100 text-sm font-medium">Total Arsip</p>
                                    <p class="text-3xl font-bold text-white">{{ $bpkRiTotal + $bpkpTotal }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-400/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-folder text-blue-200 text-xl"></i>
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
                        Upload Dokumen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div
        class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-2">
        <div class="flex flex-col md:flex-row gap-2">
            <div class="flex-1 flex bg-gray-100 dark:bg-gray-700 rounded-xl p-1">
                <button wire:click="setActiveTab('bpk_ri')"
                    class="flex-1 flex items-center justify-center gap-3 px-6 py-3 rounded-lg font-semibold transition-all duration-300 {{ $activeTab === 'bpk_ri' ? 'bg-white dark:bg-gray-600 text-red-600 dark:text-red-400 shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400' }}">
                    <i class="fas fa-building text-lg"></i>
                    <span>BPK RI</span>
                    @if ($bpkRiTotal > 0)
                        <span
                            class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-2 py-1 rounded-full text-xs font-bold">
                            {{ $bpkRiTotal }}
                        </span>
                    @endif
                </button>
                <button wire:click="setActiveTab('bpkp')"
                    class="flex-1 flex items-center justify-center gap-3 px-6 py-3 rounded-lg font-semibold transition-all duration-300 {{ $activeTab === 'bpkp' ? 'bg-white dark:bg-gray-600 text-green-600 dark:text-green-400 shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400' }}">
                    <i class="fas fa-university text-lg"></i>
                    <span>BPKP</span>
                    @if ($bpkpTotal > 0)
                        <span
                            class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-2 py-1 rounded-full text-xs font-bold">
                            {{ $bpkpTotal }}
                        </span>
                    @endif
                </button>
            </div>

            <!-- Search -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-lg"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Cari nama file atau keterangan..."
                    class="w-full pl-12 pr-4 py-3 bg-white/80 dark:bg-gray-700/80 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-cyan-500/20 focus:border-cyan-500 dark:focus:border-cyan-400 transition-all text-gray-900 dark:text-white placeholder-gray-500">
            </div>
        </div>
    </div>

    <!-- Files Grid -->
    @if ($activeTab === 'bpk_ri')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($bpkRiArsips as $arsip)
                <div wire:key="bpk_ri_{{ $arsip->id }}"
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:scale-[1.02]">
                    <!-- File Preview -->
                    <div
                        class="relative bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 p-6 h-32 flex items-center justify-center">
                        <i class="{{ $arsip->file_icon }} text-4xl"></i>
                        <div class="absolute top-3 right-3 bg-white/90 dark:bg-gray-800/90 rounded-lg px-2 py-1">
                            <span
                                class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">{{ $arsip->file_extension }}</span>
                        </div>
                    </div>

                    <!-- File Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white truncate mb-2"
                            title="{{ $arsip->file_name }}">
                            {{ $arsip->file_name }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                            {{ $arsip->keterangan }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                            <span>{{ $arsip->formatted_file_size }}</span>
                            <span>{{ $arsip->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-t border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if ($arsip->is_pdf || $arsip->is_image)
                                    <button wire:click="preview('{{ $arsip->id }}')"
                                        class="group/btn flex items-center gap-1 px-2 py-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg transition-all duration-200 hover:scale-105">
                                        <i
                                            class="fas fa-eye text-xs group-hover/btn:scale-110 transition-transform"></i>
                                        <span class="text-xs font-medium">Preview</span>
                                    </button>
                                @endif
                                <button wire:click="download('{{ $arsip->id }}')"
                                    class="group/btn flex items-center gap-1 px-2 py-1 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 rounded-lg transition-all duration-200 hover:scale-105">
                                    <i
                                        class="fas fa-download text-xs group-hover/btn:scale-110 transition-transform"></i>
                                    <span class="text-xs font-medium">Download</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-1">
                                <button wire:click="edit('{{ $arsip->id }}')"
                                    class="group/btn p-2 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 rounded-lg transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-edit text-xs group-hover/btn:scale-110 transition-transform"></i>
                                </button>
                                <button wire:click="delete('{{ $arsip->id }}')"
                                    wire:confirm="Anda yakin ingin menghapus arsip ini?"
                                    class="group/btn p-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-trash text-xs group-hover/btn:scale-110 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State BPK RI -->
                <div class="col-span-full">
                    <div
                        class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-3xl p-12 text-center border-2 border-dashed border-red-300 dark:border-red-700">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-file-pdf text-4xl text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Belum Ada Arsip BPK RI</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                            Mulai dengan mengunggah dokumen BPK RI pertama untuk memulai sistem arsip digital.
                        </p>
                        <button wire:click="create"
                            class="inline-flex items-center gap-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-8 py-4 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus"></i>
                            Upload Dokumen BPK RI
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($bpkpArsips as $arsip)
                <div wire:key="bpkp_{{ $arsip->id }}"
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:scale-[1.02]">
                    <!-- File Preview -->
                    <div
                        class="relative bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 h-32 flex items-center justify-center">
                        <i class="{{ $arsip->file_icon }} text-4xl"></i>
                        <div class="absolute top-3 right-3 bg-white/90 dark:bg-gray-800/90 rounded-lg px-2 py-1">
                            <span
                                class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">{{ $arsip->file_extension }}</span>
                        </div>
                    </div>

                    <!-- File Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white truncate mb-2"
                            title="{{ $arsip->file_name }}">
                            {{ $arsip->file_name }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                            {{ $arsip->keterangan }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                            <span>{{ $arsip->formatted_file_size }}</span>
                            <span>{{ $arsip->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-t border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if ($arsip->is_pdf || $arsip->is_image)
                                    <button wire:click="preview('{{ $arsip->id }}')"
                                        class="group/btn flex items-center gap-1 px-2 py-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg transition-all duration-200 hover:scale-105">
                                        <i
                                            class="fas fa-eye text-xs group-hover/btn:scale-110 transition-transform"></i>
                                        <span class="text-xs font-medium">Preview</span>
                                    </button>
                                @endif
                                <button wire:click="download('{{ $arsip->id }}')"
                                    class="group/btn flex items-center gap-1 px-2 py-1 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 rounded-lg transition-all duration-200 hover:scale-105">
                                    <i
                                        class="fas fa-download text-xs group-hover/btn:scale-110 transition-transform"></i>
                                    <span class="text-xs font-medium">Download</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-1">
                                <button wire:click="edit('{{ $arsip->id }}')"
                                    class="group/btn p-2 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 rounded-lg transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-edit text-xs group-hover/btn:scale-110 transition-transform"></i>
                                </button>
                                <button wire:click="delete('{{ $arsip->id }}')"
                                    wire:confirm="Anda yakin ingin menghapus arsip ini?"
                                    class="group/btn p-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-all duration-200 hover:scale-105">
                                    <i class="fas fa-trash text-xs group-hover/btn:scale-110 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State BPKP -->
                <div class="col-span-full">
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-3xl p-12 text-center border-2 border-dashed border-green-300 dark:border-green-700">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-file-alt text-4xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Belum Ada Arsip BPKP</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                            Mulai dengan mengunggah dokumen BPKP pertama untuk memulai sistem arsip digital.
                        </p>
                        <button wire:click="create"
                            class="inline-flex items-center gap-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-4 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus"></i>
                            Upload Dokumen BPKP
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    @endif

    <!-- Enhanced Pagination -->
    @if (($activeTab === 'bpk_ri' && $bpkRiArsips->hasPages()) || ($activeTab === 'bpkp' && $bpkpArsips->hasPages()))
        <div
            class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 p-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                @if ($activeTab === 'bpk_ri')
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan {{ $bpkRiArsips->firstItem() }} - {{ $bpkRiArsips->lastItem() }} dari
                        {{ $bpkRiArsips->total() }} hasil
                    </div>
                    <div class="flex items-center gap-2">
                        {{ $bpkRiArsips->links() }}
                    </div>
                @else
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan {{ $bpkpArsips->firstItem() }} - {{ $bpkpArsips->lastItem() }} dari
                        {{ $bpkpArsips->total() }} hasil
                    </div>
                    <div class="flex items-center gap-2">
                        {{ $bpkpArsips->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Upload Modal -->
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
                    class="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 px-8 py-6 border-b border-gray-100 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-cloud-upload-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $arsip_id ? 'Edit' : 'Upload' }} Dokumen
                                    {{ $activeTab === 'bpk_ri' ? 'BPK RI' : 'BPKP' }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $arsip_id ? 'Perbarui informasi dokumen yang ada' : 'Unggah dokumen baru ke sistem arsip' }}
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
                        <!-- File Upload -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-file text-cyan-600 dark:text-cyan-400 mr-2"></i>
                                File Dokumen {{ $arsip_id ? '(Kosongkan jika tidak ingin mengubah)' : '' }}
                            </label>
                            <div class="relative">
                                <input type="file" wire:model="form.file"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-cyan-500/20 focus:border-cyan-500 dark:focus:border-cyan-400 transition-all text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                                <div wire:loading wire:target="form.file"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-spinner animate-spin text-cyan-600"></i>
                                </div>
                            </div>
                            @if ($errors->has('form.file'))
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $errors->first('form.file') }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP, RAR. Maksimal
                                200MB.
                            </p>
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2">
                            <label for="keterangan" class="block text-sm font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-comment-alt text-cyan-600 dark:text-cyan-400 mr-2"></i>
                                Keterangan
                            </label>
                            <textarea id="keterangan" wire:model="form.keterangan" rows="4"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:ring-4 focus:ring-cyan-500/20 focus:border-cyan-500 dark:focus:border-cyan-400 transition-all text-gray-900 dark:text-white placeholder-gray-500"
                                placeholder="Masukkan keterangan atau deskripsi dokumen..."></textarea>
                            @if ($errors->has('form.keterangan'))
                                <p class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $errors->first('form.keterangan') }}
                                </p>
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
                            class="group px-8 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white rounded-xl font-semibold transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-3 min-w-[140px] justify-center">
                            <span wire:loading.remove wire:target="store" class="flex items-center gap-2">
                                <i class="fas fa-cloud-upload-alt group-hover:scale-110 transition-transform"></i>
                                {{ $arsip_id ? 'Perbarui' : 'Upload' }}
                            </span>
                            <span wire:loading wire:target="store" class="flex items-center gap-2">
                                <i class="fas fa-spinner animate-spin"></i>
                                {{ $arsip_id ? 'Memperbarui...' : 'Mengupload...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div x-data="{ show: @entangle('isPreviewOpen') }" x-show="show" @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="preview-title" role="dialog" aria-modal="true"
        style="display: none;">

        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity" @click="$wire.closePreview()"
                aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

                @if ($previewFile)
                    <!-- Preview Header -->
                    <div
                        class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="{{ $previewFile->file_icon }} text-2xl"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $previewFile->file_name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $previewFile->formatted_file_size }} •
                                        {{ $previewFile->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <button @click="$wire.closePreview()"
                                class="w-10 h-10 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl flex items-center justify-center transition-colors">
                                <i class="fas fa-times text-gray-600 dark:text-gray-400"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Preview Content -->
                    <div class="p-6 max-h-96 overflow-auto">
                        @if ($previewFile->is_pdf)
                            <iframe src="{{ Storage::url($previewFile->file_path) }}"
                                class="w-full h-96 rounded-lg border"></iframe>
                        @elseif($previewFile->is_image)
                            <img src="{{ Storage::url($previewFile->file_path) }}"
                                alt="{{ $previewFile->file_name }}" class="max-w-full h-auto rounded-lg mx-auto">
                        @else
                            <div class="text-center py-12">
                                <i class="{{ $previewFile->file_icon }} text-6xl mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400">Preview tidak tersedia untuk jenis file
                                    ini.</p>
                                <button wire:click="download('{{ $previewFile->id }}')"
                                    class="mt-4 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all">
                                    <i class="fas fa-download"></i>
                                    Download File
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
