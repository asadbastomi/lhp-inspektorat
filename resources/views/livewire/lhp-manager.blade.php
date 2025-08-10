<div>
<div class="p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold leading-6 text-gray-900">Laporan Hasil Pemeriksaan (LHP)</h1>
            <p class="mt-2 text-sm text-gray-700">Daftar semua LHP yang telah tercatat dalam sistem.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            @if($user->role === 'admin')
            <button wire:click="create()" type="button" class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i>Tambah LHP
            </button>
            @endif
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mt-4">
        <input type="text" wire:model.live="search" placeholder="Cari berdasarkan judul atau nomor LHP..." 
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
    </div>

    @if (session()->has('message'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mt-4 rounded-md bg-green-50 p-4 transition-opacity">
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

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mt-4 rounded-md bg-red-50 p-4 transition-opacity">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    <button wire:click="sortBy('nomor_lhp')" class="group inline-flex">
                                        Nomor LHP
                                        @if($sortField === 'nomor_lhp')
                                            <span class="ml-2">
                                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                            </span>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    <button wire:click="sortBy('judul_lhp')" class="group inline-flex">
                                        Judul LHP
                                        @if($sortField === 'judul_lhp')
                                            <span class="ml-2">
                                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                            </span>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Irban</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    <button wire:click="sortBy('tanggal_lhp')" class="group inline-flex">
                                        Tanggal LHP
                                        @if($sortField === 'tanggal_lhp')
                                            <span class="ml-2">
                                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                            </span>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Lama Penugasan</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($lhps as $lhp)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $lhp->nomor_lhp }}</td>
                                <td class="px-3 py-4 text-sm text-gray-500">{{ Str::limit($lhp->judul_lhp, 50) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $lhp->user->name ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $lhp->tanggal_lhp->format('d M Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $lhp->lama_penugasan }} hari</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- Detail Button -->
                                        <a href="{{ route('lhp.detail', $lhp->id) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($user->role === 'irban' && $lhp->user_id === $user->id && !$lhp->file_lhp)
                                        <button wire:click="prepareUpload('{{ $lhp->id }}')"
                                            class="text-green-600 hover:text-green-900"
                                            title="Upload LHP">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                        @elseif($lhp->file_lhp)
                                        <button wire:click="downloadFile('{{ $lhp->id }}')"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Download LHP">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                        @endif

                                        @if($user->role === 'admin')
                                        @if(!$lhp->file_lhp)
                                        <button wire:click="prepareUpload('{{ $lhp->id }}')"
                                            class="text-green-600 hover:text-green-900"
                                            title="Upload LHP">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                        @endif
                                        <button wire:click="edit('{{ $lhp->id }}')"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete('{{ $lhp->id }}')"
                                            wire:confirm="Apakah Anda yakin ingin menghapus LHP ini?"
                                            class="text-red-600 hover:text-red-900"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-sm text-gray-500">Tidak ada data LHP ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $lhps->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500/75 backdrop-blur-sm" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                    {{ $lhp_id ? 'Edit' : 'Tambah' }} LHP
                </h3>
                <form wire:submit.prevent="store">
                    <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                        <div class="sm:col-span-2">
                            <label for="judul_lhp" class="block text-sm font-medium text-gray-700">Judul LHP</label>
                            <input type="text" wire:model="judul_lhp" id="judul_lhp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('judul_lhp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Pilih Irban</label>
                            <select wire:model="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">-- Pilih Irban --</option>
                                @foreach($irbans as $irban)
                                <option value="{{ $irban->id }}">{{ $irban->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="nomor_lhp" class="block text-sm font-medium text-gray-700">Nomor LHP</label>
                            <input type="text" wire:model="nomor_lhp" id="nomor_lhp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('nomor_lhp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="tanggal_lhp" class="block text-sm font-medium text-gray-700">Tanggal LHP</label>
                            <input type="date" wire:model="tanggal_lhp" id="tanggal_lhp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('tanggal_lhp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="nomor_surat_tugas" class="block text-sm font-medium text-gray-700">Nomor Surat Tugas</label>
                            <input type="text" wire:model="nomor_surat_tugas" id="nomor_surat_tugas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('nomor_surat_tugas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="tanggal_penugasan" class="block text-sm font-medium text-gray-700">Tanggal Penugasan</label>
                            <input type="date" wire:model="tanggal_penugasan" id="tanggal_penugasan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('tanggal_penugasan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="lama_penugasan" class="block text-sm font-medium text-gray-700">Lama Penugasan (hari)</label>
                            <input type="number" wire:model="lama_penugasan" id="lama_penugasan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('lama_penugasan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" wire:click="closeModal()" class="mt-3 inline-flex justify-center w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Upload Modal - Inside main container -->
<div x-data="uploadModal()" 
     x-show="show"
     x-on:open-upload-modal.window="openModal($event)"
     x-on:close-upload-modal.window="closeModal()"
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="upload-modal-title"
     role="dialog"
     aria-modal="true">
    
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"
             class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
             aria-hidden="true">
        </div>

        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop
             class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">

            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button @click="closeModal()" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="upload-modal-title">
                    Upload File LHP
                </h3>
                
                <div class="mt-3">
                    <p class="text-sm text-gray-600">
                        Upload file LHP untuk <span class="font-medium" x-text="uploadingLhpNumber"></span>.
                        Maksimal ukuran file: 200MB. Format yang didukung: PDF.
                    </p>
                </div>

                <div class="mt-4">
                    <div x-ref="dropZone" 
                         @drop.prevent="handleDrop($event)"
                         @dragover.prevent="dragOver = true"
                         @dragleave.prevent="dragOver = false"
                         :class="{'border-blue-400 bg-blue-50': dragOver}"
                         class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                        
                        <input type="file" 
                               x-ref="fileInput" 
                               @change="handleFileSelect($event)"
                               class="hidden" 
                               accept=".pdf">
                        
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        
                        <p class="mt-2 text-sm text-gray-600">
                            <button type="button" 
                                    @click="$refs.fileInput.click()" 
                                    class="font-medium text-blue-600 hover:text-blue-500">
                                Klik untuk upload
                            </button>
                            atau drag and drop
                        </p>
                        <p class="text-xs text-gray-500">PDF hingga 200MB</p>
                    </div>

                    <!-- Progress Bar -->
                    <div x-show="uploadProgress > 0 && uploadProgress < 100" class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                                 :style="`width: ${uploadProgress}%`">
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-600 text-right">
                            <span x-text="Math.round(uploadProgress)"></span>% selesai
                        </p>
                    </div>

                    <!-- Success Message -->
                    <div x-show="uploadProgress === 100" class="mt-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    File berhasil diunggah!
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div x-show="error" class="mt-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800" x-text="error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-6">
                <button @click="closeModal()" 
                        type="button" 
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
@push('scripts')
<script>
function uploadModal() {
    return {
        show: false,
        uploadingLhpNumber: '',
        uploadingLhpId: null,
        resumable: null,
        uploadProgress: 0,
        error: null,
        dragOver: false,

        openModal(event) {
            this.show = true;
            this.uploadingLhpNumber = event.detail.lhpNumber || '';
            this.uploadingLhpId = event.detail.lhpId || null;
            this.error = null;
            this.uploadProgress = 0;
            
            // Initialize resumable after modal opens
            this.$nextTick(() => {
                this.initResumable();
            });
        },

        closeModal() {
            this.show = false;
            if (this.resumable) {
                this.resumable.cancel();
                this.resumable = null;
            }
            this.uploadProgress = 0;
            this.error = null;
        },

        initResumable() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            if (!csrfToken) {
                this.error = 'Security token missing';
                return;
            }

            this.resumable = new Resumable({
                target: '/resumable-upload',
                chunkSize: 2 * 1024 * 1024,
                simultaneousUploads: 1,
                testChunks: true,
                maxFiles: 1,
                fileType: ['pdf'],
                maxFileSize: 200 * 1024 * 1024,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                query: {
                    '_token': csrfToken,
                    'lhp_id': this.uploadingLhpId
                }
            });

            if (!this.resumable.support) {
                this.error = 'Your browser does not support file uploads.';
                return;
            }

            // Assign drop zone and file input
            this.resumable.assignDrop(this.$refs.dropZone);
            this.resumable.assignBrowse(this.$refs.fileInput);

            // Setup event handlers
            this.resumable.on('fileAdded', (file) => {
                console.log('File added:', file.fileName);
                this.error = null;
                this.uploadProgress = 0;
                this.resumable.upload();
            });

            this.resumable.on('fileProgress', (file) => {
                this.uploadProgress = Math.floor(file.progress() * 100);
            });

            this.resumable.on('fileSuccess', (file, message) => {
                try {
                    const response = JSON.parse(message);
                    if (response.success) {
                        this.uploadProgress = 100;
                        
                        // Notify Livewire
                        if (window.Livewire) {
                            Livewire.emit('fileUploaded', {
                                lhpId: this.uploadingLhpId,
                                filePath: response.data.path,
                                fileName: response.data.filename
                            });
                        }
                        
                        setTimeout(() => {
                            this.closeModal();
                            location.reload();
                        }, 2000);
                    }
                } catch (e) {
                    console.log(e);
                    this.error = 'Upload completed but failed to process response';
                }
            });

            this.resumable.on('fileError', (file, message) => {
                this.error = `Upload failed: ${message}`;
                this.uploadProgress = 0;
            });
        },

        handleDrop(event) {
            this.dragOver = false;
            // Resumable handles the drop internally
        },

        handleFileSelect(event) {
            // Resumable handles the file selection internally
        }
    }
}

// Listen for Livewire events
document.addEventListener('DOMContentLoaded', function() {
    if (window.Livewire) {
        Livewire.on('openUploadModal', (data) => {
            window.dispatchEvent(new CustomEvent('open-upload-modal', { 
                detail: data 
            }));
        });
    }
});
</script>
@endpush