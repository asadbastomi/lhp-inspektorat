<div class="p-4 sm:p-6 lg:p-8">
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="mb-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Detail LHP: {{ $lhp->judul_lhp }}</h1>
            <a href="{{ route('lhps') }}" class="text-sm text-blue-600 hover:text-blue-800">
                &larr; Kembali ke Daftar LHP
            </a>
        </div>
        <p class="mt-1 text-sm text-gray-600">Nomor: {{ $lhp->nomor_lhp }} | Tanggal: {{ $lhp->tanggal_lhp->format('d F Y') }}</p>
    </div>

    <form wire:submit.prevent="save">
        <!-- Group 1: File Uploads -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Dokumen LHP</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Unggah dokumen-dokumen terkait LHP</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <!-- File Surat Tugas -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">File Surat Tugas</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if($lhp->file_surat_tugas)
                                <div class="flex items-center">
                                    <a href="{{ Storage::url($lhp->file_surat_tugas) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file-pdf mr-2"></i>Lihat File
                                    </a>
                                    <button type="button" wire:click="deleteFile('file_surat_tugas')" class="ml-4 text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <input type="file" wire:model="file_surat_tugas" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('file_surat_tugas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    
                                    <div class="upload-progress hidden space-y-1 mt-2" 
                                         x-data="{ 
                                            progress: 0, 
                                            speed: 0, 
                                            timeRemaining: 'Menghitung...', 
                                            uploaded: 0, 
                                            total: 0,
                                            formatFileSize(bytes) {
                                                if (!bytes || bytes === 0) return '0 Bytes';
                                                const k = 1024;
                                                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                                                const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                                            }
                                         }" 
                                         x-init="
                                            $watch('progress', value => {
                                                const progressBar = $el.querySelector('.progress-bar');
                                                if (progressBar) {
                                                    progressBar.style.width = value + '%';
                                                    progressBar.classList.toggle('bg-blue-600', value < 100);
                                                    progressBar.classList.toggle('bg-green-500', value === 100);
                                                }
                                            });
                                            
                                            // Listen for progress updates
                                            window.addEventListener('upload-progress-updated', (e) => {
                                                if (e.detail.name === 'file_surat_tugas') {
                                                    progress = e.detail.progress;
                                                    const fileInput = $el.closest('dd').querySelector('input[type=file]');
                                                    if (fileInput && fileInput.files[0]) {
                                                        uploaded = fileInput.files[0].size * (progress / 100);
                                                        total = fileInput.files[0].size;
                                                    }
                                                }
                                            });
                                         ">
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span class="font-medium">Mengunggah...</span>
                                            <span x-text="timeRemaining"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-blue-600 h-2 rounded-full progress-bar transition-all duration-300 ease-in-out" 
                                                 :class="{ 'animate-pulse': progress < 100 }">
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500">
                                            <span x-text="formatFileSize(uploaded) + ' / ' + formatFileSize(total)"></span>
                                            <span x-text="(progress || 0).toFixed(1) + '%'"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </dd>
                    </div>
                    
                    <!-- File LHP -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">File LHP</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if($lhp->file_lhp)
                                <div class="flex items-center">
                                    <a href="{{ Storage::url($lhp->file_lhp) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file-pdf mr-2"></i>Lihat File
                                    </a>
                                    <button type="button" wire:click="deleteFile('file_lhp')" class="ml-4 text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <input type="file" wire:model="file_lhp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('file_lhp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    
                                    <div class="upload-progress hidden space-y-1 mt-2" 
                                         x-data="{ 
                                            progress: 0, 
                                            speed: 0, 
                                            timeRemaining: 'Menghitung...', 
                                            uploaded: 0, 
                                            total: 0,
                                            formatFileSize(bytes) {
                                                if (!bytes || bytes === 0) return '0 Bytes';
                                                const k = 1024;
                                                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                                                const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                                            }
                                         }" 
                                         x-init="
                                            $watch('progress', value => {
                                                const progressBar = $el.querySelector('.progress-bar');
                                                if (progressBar) {
                                                    progressBar.style.width = value + '%';
                                                    progressBar.classList.toggle('bg-blue-600', value < 100);
                                                    progressBar.classList.toggle('bg-green-500', value === 100);
                                                }
                                            });
                                            
                                            // Listen for progress updates
                                            window.addEventListener('upload-progress-updated', (e) => {
                                                if (e.detail.name === 'file_lhp') {
                                                    progress = e.detail.progress;
                                                    const fileInput = $el.closest('dd').querySelector('input[type=file]');
                                                    if (fileInput && fileInput.files[0]) {
                                                        uploaded = fileInput.files[0].size * (progress / 100);
                                                        total = fileInput.files[0].size;
                                                    }
                                                }
                                            });
                                         ">
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span class="font-medium">Mengunggah...</span>
                                            <span x-text="timeRemaining"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-blue-600 h-2 rounded-full progress-bar transition-all duration-300 ease-in-out" 
                                                 :class="{ 'animate-pulse': progress < 100 }">
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500">
                                            <span x-text="formatFileSize(uploaded) + ' / ' + formatFileSize(total)"></span>
                                            <span x-text="(progress || 0).toFixed(1) + '%'"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </dd>
                    </div>
                    
                    <!-- File Kertas Kerja -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">File Kertas Kerja</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if($lhp->file_kertas_kerja)
                                <div class="flex items-center">
                                    <a href="{{ Storage::url($lhp->file_kertas_kerja) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file-pdf mr-2"></i>Lihat File
                                    </a>
                                    <button type="button" wire:click="deleteFile('file_kertas_kerja')" class="ml-4 text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <input type="file" wire:model="file_kertas_kerja" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('file_kertas_kerja') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    
                                    <div class="upload-progress hidden space-y-1 mt-2" 
                                         x-data="{ 
                                            progress: 0, 
                                            speed: 0, 
                                            timeRemaining: 'Menghitung...', 
                                            uploaded: 0, 
                                            total: 0,
                                            formatFileSize(bytes) {
                                                if (!bytes || bytes === 0) return '0 Bytes';
                                                const k = 1024;
                                                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                                                const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                                            }
                                         }" 
                                         x-init="
                                            $watch('progress', value => {
                                                const progressBar = $el.querySelector('.progress-bar');
                                                if (progressBar) {
                                                    progressBar.style.width = value + '%';
                                                    progressBar.classList.toggle('bg-blue-600', value < 100);
                                                    progressBar.classList.toggle('bg-green-500', value === 100);
                                                }
                                            });
                                            
                                            // Listen for progress updates
                                            window.addEventListener('upload-progress-updated', (e) => {
                                                if (e.detail.name === 'file_kertas_kerja') {
                                                    progress = e.detail.progress;
                                                    const fileInput = $el.closest('dd').querySelector('input[type=file]');
                                                    if (fileInput && fileInput.files[0]) {
                                                        uploaded = fileInput.files[0].size * (progress / 100);
                                                        total = fileInput.files[0].size;
                                                    }
                                                }
                                            });
                                         ">
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span class="font-medium">Mengunggah...</span>
                                            <span x-text="timeRemaining"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-blue-600 h-2 rounded-full progress-bar transition-all duration-300 ease-in-out" 
                                                 :class="{ 'animate-pulse': progress < 100 }">
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500">
                                            <span x-text="formatFileSize(uploaded) + ' / ' + formatFileSize(total)"></span>
                                            <span x-text="(progress || 0).toFixed(1) + '%'"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </dd>
                    </div>
                    
                    <!-- File Review Sheet -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">File Review Sheet</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if($lhp->file_review_sheet)
                                <div class="flex items-center">
                                    <a href="{{ Storage::url($lhp->file_review_sheet) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file-pdf mr-2"></i>Lihat File
                                    </a>
                                    <button type="button" wire:click="deleteFile('file_review_sheet')" class="ml-4 text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <input type="file" wire:model="file_review_sheet" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('file_review_sheet') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    
                                    <div class="upload-progress hidden space-y-1 mt-2" 
                                         x-data="{ 
                                            progress: 0, 
                                            speed: 0, 
                                            timeRemaining: 'Menghitung...', 
                                            uploaded: 0, 
                                            total: 0,
                                            formatFileSize(bytes) {
                                                if (!bytes || bytes === 0) return '0 Bytes';
                                                const k = 1024;
                                                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                                                const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                                            }
                                         }" 
                                         x-init="
                                            $watch('progress', value => {
                                                const progressBar = $el.querySelector('.progress-bar');
                                                if (progressBar) {
                                                    progressBar.style.width = value + '%';
                                                    progressBar.classList.toggle('bg-blue-600', value < 100);
                                                    progressBar.classList.toggle('bg-green-500', value === 100);
                                                }
                                            });
                                            
                                            // Listen for progress updates
                                            window.addEventListener('upload-progress-updated', (e) => {
                                                if (e.detail.name === 'file_review_sheet') {
                                                    progress = e.detail.progress;
                                                    const fileInput = $el.closest('dd').querySelector('input[type=file]');
                                                    if (fileInput && fileInput.files[0]) {
                                                        uploaded = fileInput.files[0].size * (progress / 100);
                                                        total = fileInput.files[0].size;
                                                    }
                                                }
                                            });
                                         ">
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span class="font-medium">Mengunggah...</span>
                                            <span x-text="timeRemaining"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-blue-600 h-2 rounded-full progress-bar transition-all duration-300 ease-in-out" 
                                                 :class="{ 'animate-pulse': progress < 100 }">
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500">
                                            <span x-text="formatFileSize(uploaded) + ' / ' + formatFileSize(total)"></span>
                                            <span x-text="(progress || 0).toFixed(1) + '%'"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </dd>
                    </div>
                    
                    <!-- File Nota Dinas -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">File Nota Dinas</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if($lhp->file_nota_dinas)
                                <div class="flex items-center">
                                    <a href="{{ Storage::url($lhp->file_nota_dinas) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file-pdf mr-2"></i>Lihat File
                                    </a>
                                    <button type="button" wire:click="deleteFile('file_nota_dinas')" class="ml-4 text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <input type="file" wire:model="file_nota_dinas" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('file_nota_dinas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    
                                    <div class="upload-progress hidden space-y-1 mt-2" 
                                         x-data="{ 
                                            progress: 0, 
                                            speed: 0, 
                                            timeRemaining: 'Menghitung...', 
                                            uploaded: 0, 
                                            total: 0,
                                            formatFileSize(bytes) {
                                                if (!bytes || bytes === 0) return '0 Bytes';
                                                const k = 1024;
                                                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                                                const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                                            }
                                         }" 
                                         x-init="
                                            $watch('progress', value => {
                                                const progressBar = $el.querySelector('.progress-bar');
                                                if (progressBar) {
                                                    progressBar.style.width = value + '%';
                                                    progressBar.classList.toggle('bg-blue-600', value < 100);
                                                    progressBar.classList.toggle('bg-green-500', value === 100);
                                                }
                                            });
                                            
                                            // Listen for progress updates
                                            window.addEventListener('upload-progress-updated', (e) => {
                                                if (e.detail.name === 'file_nota_dinas') {
                                                    progress = e.detail.progress;
                                                    const fileInput = $el.closest('dd').querySelector('input[type=file]');
                                                    if (fileInput && fileInput.files[0]) {
                                                        uploaded = fileInput.files[0].size * (progress / 100);
                                                        total = fileInput.files[0].size;
                                                    }
                                                }
                                            });
                                         ">
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span class="font-medium">Mengunggah...</span>
                                            <span x-text="timeRemaining"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-blue-600 h-2 rounded-full progress-bar transition-all duration-300 ease-in-out" 
                                                 :class="{ 'animate-pulse': progress < 100 }">
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500">
                                            <span x-text="formatFileSize(uploaded) + ' / ' + formatFileSize(total)"></span>
                                            <span x-text="(progress || 0).toFixed(1) + '%'"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Rest of the form remains the same... -->
        <!-- Group 2: Findings -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Temuan dan Rekomendasi</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Masukkan temuan dan rekomendasi dari pemeriksaan</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <!-- Temuan -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Temuan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <textarea wire:model="temuan" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                            @error('temuan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </dd>
                    </div>
                    
                    <!-- Rincian Rekomendasi -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Rincian Rekomendasi</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <textarea wire:model="rincian_rekomendasi" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                            @error('rincian_rekomendasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </dd>
                    </div>
                    
                    <!-- Besaran Temuan -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Besaran Temuan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" wire:model="besaran_temuan" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('besaran_temuan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </dd>
                    </div>
                    
                    <!-- Tindak Lanjut -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Tindak Lanjut</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <textarea wire:model="tindak_lanjut" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                            @error('tindak_lanjut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .progress-bar {
        transition: width 0.3s ease-in-out;
    }
    .animate-pulse-slow {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    /* SweetAlert2 Customizations */
    .swal2-popup {
        font-size: 0.9rem !important;
    }
    .swal2-title {
        font-size: 1.2rem !important;
    }
</style>
@endpush

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        // Get the LHP ID from the Livewire component
        const lhpId = @json($lhp->id);
        
        // Handle file input changes - escape the colon in wire:model
        document.querySelectorAll('input[type="file"][wire\\:model]').forEach(input => {
            input.addEventListener('change', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const fieldName = this.getAttribute('wire:model');
                const file = this.files[0];
                const fileInput = this; // Store reference to the input
                
                if (!file) return;
                
                // Validate file type
                const validTypes = ['application/pdf'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format File Tidak Valid',
                        text: 'Hanya file PDF yang diizinkan.',
                        confirmButtonText: 'Mengerti'
                    });
                    this.value = ''; // Clear the file input
                    return;
                }
                
                // Validate file size (200MB)
                const maxSize = 200 * 1024 * 1024; // 200MB in bytes
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Ukuran file melebihi batas maksimal 200MB.',
                        confirmButtonText: 'Mengerti'
                    });
                    this.value = ''; // Clear the file input
                    return;
                }
                
                // Create a fresh FormData instance
                const formData = new FormData();
                
                // Append the file with the correct field name
                formData.append('file', file);
                formData.append('field_name', fieldName);
                formData.append('lhp_id', lhpId); // Add the LHP ID
                
                // Log file info for debugging
                console.log('File to upload:', {
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    lastModified: file.lastModified,
                    lhpId: lhpId
                });
                
                // Show progress container
                const progressContainer = this.closest('dd').querySelector('.upload-progress');
                if (progressContainer) {
                    progressContainer.classList.remove('hidden');
                    // Clear any previous errors
                    const errorElement = progressContainer.querySelector('.text-red-500');
                    if (errorElement) errorElement.textContent = '';
                }
                
                // Create a new XMLHttpRequest
                const xhr = new XMLHttpRequest();
                
                // Configure the request
                const uploadUrl = '{{ route('livewire.upload-file') }}';
                console.log('Upload URL:', uploadUrl); // Debug log
                xhr.open('POST', uploadUrl, true);
                
                // Set CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                
                // Handle upload progress
                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = Math.round((e.loaded / e.total) * 100);
                        console.log(`Upload progress: ${percentComplete}%`);
                        
                        // Dispatch progress event for Alpine.js
                        window.dispatchEvent(new CustomEvent('upload-progress-updated', {
                            detail: {
                                name: fieldName,
                                progress: percentComplete
                            }
                        }));
                        
                        // Update Livewire component with progress
                        @this.updateUploadProgress(
                            fieldName, 
                            percentComplete,
                            e.loaded,
                            e.total
                        );
                    }
                };
                
                // Handle successful upload
                xhr.onload = () => {
                    console.log('Upload complete. Status:', xhr.status);
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Hide progress container after successful upload
                                if (progressContainer) {
                                    setTimeout(() => {
                                        progressContainer.classList.add('hidden');
                                    }, 1000);
                                }
                                
                                // Reset the input to allow re-uploading the same file
                                fileInput.value = '';
                                
                                // Refresh the Livewire component to show the uploaded file
                                @this.refresh();
                                
                                // Show success message with SweetAlert2
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'File berhasil diunggah',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer);
                                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                                    }
                                });
                            } else {
                                showUploadError(progressContainer, response.message || 'Upload gagal');
                            }
                        } catch (e) {
                            console.error('Error parsing upload response:', e);
                            showUploadError(progressContainer, 'Terjadi kesalahan saat memproses respons');
                        }
                    } else {
                        let errorMessage = `Server error: ${xhr.status} ${xhr.statusText}`;
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMessage = response.message || errorMessage;
                        } catch (e) {}
                        showUploadError(progressContainer, errorMessage);
                    }
                };
                
                // Handle upload errors
                xhr.onerror = () => {
                    console.error('Upload error occurred');
                    showUploadError(progressContainer, 'Kesalahan koneksi saat mengunggah file');
                };
                
                // Handle timeout
                xhr.ontimeout = () => {
                    console.error('Upload timed out');
                    showUploadError(progressContainer, 'Waktu unggah habis. Silakan coba lagi.');
                };
                
                // Set timeout (5 minutes)
                xhr.timeout = 5 * 60 * 1000;
                
                // Function to show upload errors
                function showUploadError(container, message) {
                    console.error('Upload error:', message);
                    // Show SweetAlert2 error
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Gagal',
                        text: message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                }
                
                // Show loading notification with SweetAlert2
                let timerInterval;
                Swal.fire({
                    title: 'Mengunggah File',
                    html: `Sedang mengunggah <b>${file.name}</b>...`, 
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const b = Swal.getHtmlContainer().querySelector('b');
                        timerInterval = setInterval(() => {
                            const progress = document.querySelector(`#progress-${fieldName} .progress-bar`);
                            if (progress) {
                                const percent = progress.style.width || '0%';
                                b.textContent = `Mengunggah ${percent}`;
                            }
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                });
                
                // Start the upload
                xhr.send(formData);
            });
        });
    });
</script>
@endpush