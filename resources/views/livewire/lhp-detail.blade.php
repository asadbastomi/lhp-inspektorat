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
                                <input type="file" wire:model="file_surat_tugas" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('file_surat_tugas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                                <input type="file" wire:model="file_lhp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('file_lhp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                                <input type="file" wire:model="file_kertas_kerja" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('file_kertas_kerja') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                                <input type="file" wire:model="file_review_sheet" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('file_review_sheet') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                                <input type="file" wire:model="file_nota_dinas" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('file_nota_dinas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

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

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        // Initialize any JavaScript if needed
    });
</script>
@endpush
