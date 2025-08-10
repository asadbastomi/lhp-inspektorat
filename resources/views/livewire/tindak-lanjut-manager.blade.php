<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Manajemen Tindak Lanjut</h2>
        <button wire:click="create" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Tindak Lanjut
        </button>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Cari nomor LHP, judul, atau deskripsi...">
            </div>
            
            <div class="flex-shrink-0">
                <select wire:model.live="perPage" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                    <option value="10">10 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                    <option value="100">100 per halaman</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tindak Lanjut List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('file_name')">
                            Nama File
                            @if($sortField === 'file_name')
                                @if($sortDirection === 'asc')
                                    <i class="fas fa-sort-up ml-1"></i>
                                @else
                                    <i class="fas fa-sort-down ml-1"></i>
                                @endif
                            @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            Tanggal Unggah
                            @if($sortField === 'created_at')
                                @if($sortDirection === 'asc')
                                    <i class="fas fa-sort-up ml-1"></i>
                                @else
                                    <i class="fas fa-sort-down ml-1"></i>
                                @endif
                            @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tindakLanjuts as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                        @if($item->file_type === 'image')
                                            <i class="fas fa-image"></i>
                                        @elseif($item->file_type === 'pdf')
                                            <i class="fas fa-file-pdf"></i>
                                        @elseif($item->file_type === 'document')
                                            <i class="fas fa-file-word"></i>
                                        @elseif($item->file_type === 'spreadsheet')
                                            <i class="fas fa-file-excel"></i>
                                        @elseif($item->file_type === 'video')
                                            <i class="fas fa-file-video"></i>
                                        @elseif($item->file_type === 'audio')
                                            <i class="fas fa-file-audio"></i>
                                        @else
                                            <i class="fas fa-file"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->file_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $this->formatFileSize($item->file_size) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $item->lhp->nomor_lhp }} - {{ $item->lhp->judul_lhp }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ Str::limit($item->description, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-900 mr-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="download({{ $item->id }})" class="text-green-600 hover:text-green-900 mr-2" title="Unduh">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button wire:click="$dispatch('confirm-delete', { id: {{ $item->id }}, name: '{{ $item->file_name }}' })" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data tindak lanjut yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $tindakLanjuts->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <x-modal wire:model="isModalOpen">
        <x-slot name="title">
            {{ $tindakLanjutId ? 'Edit Tindak Lanjut' : 'Tambah Tindak Lanjut Baru' }}
        </x-slot>

        <div class="space-y-4">
            <div>
                <x-label for="lhp_id" value="Pilih LHP" />
                <select id="lhp_id" wire:model="lhp_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                    <option value="">-- Pilih LHP --</option>
                    @foreach($lhps as $lhp)
                        <option value="{{ $lhp->id }}">{{ $lhp->nomor_lhp }} - {{ $lhp->judul_lhp }}</option>
                    @endforeach
                </select>
                <x-input-error for="lhp_id" class="mt-1" />
            </div>

            <div>
                <x-label for="file" value="Unggah File" />
                <div class="mt-1 flex items-center">
                    <input type="file" id="file" wire:model="file" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
                </div>
                <x-input-error for="file" class="mt-1" />
                @if($file_preview)
                    <div class="mt-2">
                        <span class="text-sm text-gray-600">Preview:</span>
                        @if(Str::startsWith($file->getMimeType(), 'image/'))
                            <img src="{{ $file_preview }}" alt="Preview" class="mt-1 h-32 object-contain border rounded">
                        @elseif($file->getMimeType() === 'application/pdf')
                            <div class="mt-1 p-2 border rounded bg-gray-50 text-center">
                                <i class="fas fa-file-pdf text-red-500 text-4xl"></i>
                                <p class="text-sm text-gray-600 mt-1">PDF Preview</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <x-label for="description" value="Deskripsi (Opsional)" />
                <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"></textarea>
                <x-input-error for="description" class="mt-1" />
            </div>
        </div>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" class="mr-2">
                Batal
            </x-secondary-button>
            <x-button wire:click="store" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $tindakLanjutId ? 'Simpan Perubahan' : 'Simpan' }}</span>
                <span wire:loading>Menyimpan...</span>
            </x-button>
        </x-slot>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model="confirmingDeletion">
        <x-slot name="title">
            Hapus Tindak Lanjut
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus file ini? Tindakan ini tidak dapat dibatalkan.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingDeletion', false)" class="mr-2">
                Batal
            </x-secondary-button>
            <x-danger-button wire:click="delete" wire:loading.attr="disabled">
                <span wire:loading.remove>Hapus</span>
                <span wire:loading>Menghapus...</span>
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Handle delete confirmation
            window.addEventListener('confirm-delete', event => {
                if (confirm(`Apakah Anda yakin ingin menghapus ${event.detail.name}?`)) {
                    @this.delete(event.detail.id);
                }
            });
        });
    </script>
    @endpush
</div>
