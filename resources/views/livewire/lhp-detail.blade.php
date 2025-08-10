<div class="container mx-auto px-4 py-8" x-data="{ activeTab: window.location.hash.substring(1) || 'dokumen' }" x-init="$watch('activeTab', value => window.location.hash = value)">
    <!-- Header Card -->
    <div class="card mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[--color-dark]">
                    {{ $lhp->judul_lhp }}
                </h1>
                <div class="flex flex-wrap gap-x-4 gap-y-2 mt-4 text-sm text-gray-700">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[--color-secondary]/30 text-[--color-dark]">
                        <i class="fas fa-hashtag text-[--color-accent] mr-2"></i>{{ $lhp->nomor_lhp }}
                    </span>
                    <span class="flex items-center"><i class="far fa-calendar-alt text-[--color-accent] mr-2"></i>{{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}</span>
                    <span class="flex items-center"><i class="fas fa-user-tie text-[--color-accent] mr-2"></i>{{ $lhp->user->name ?? 'N/A' }}</span>
                </div>
            </div>
            <a href="{{ route('lhps') }}" class="w-full md:w-auto px-6 py-3 bg-[#1B5E20] text-white rounded-xl font-semibold transition-all hover:bg-[#388E3C] hover:shadow-lg hover:scale-105"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white/60 rounded-xl p-2 mb-8 shadow-sm border">
        <div class="flex flex-wrap gap-2">
            <button @click="activeTab = 'dokumen'" :class="activeTab === 'dokumen' ? 'text-white bg-[--color-accent] shadow-md' : 'text-[--color-dark] hover:bg-white/80'" class="flex-1 px-6 py-3 rounded-lg font-semibold transition-all"><i class="fas fa-file-alt mr-2"></i> Dokumen</button>
            <button @click="activeTab = 'temuan'" :class="activeTab === 'temuan' ? 'text-white bg-[--color-accent] shadow-md' : 'text-[--color-dark] hover:bg-white/80'" class="flex-1 px-6 py-3 rounded-lg font-semibold transition-all"><i class="fas fa-clipboard-list mr-2"></i> Temuan</button>
            <button @click="activeTab = 'tindak-lanjut'" :class="activeTab === 'tindak-lanjut' ? 'text-white bg-[--color-accent] shadow-md' : 'text-[--color-dark] hover:bg-white/80'" class="flex-1 px-6 py-3 rounded-lg font-semibold transition-all"><i class="fas fa-tasks mr-2"></i> Tindak Lanjut <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $lhp->tindakLanjuts->count() }}</span></button>
        </div>
    </div>

    <!-- Tab Content Area -->
    <div class="card min-h-[400px]">
        <!-- Dokumen Tab -->
        <div x-show="activeTab === 'dokumen'" x-transition.opacity.duration.500ms>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                 @foreach (['file_surat_tugas' => 'Surat Tugas', 'file_lhp' => 'File LHP', 'file_kertas_kerja' => 'Kertas Kerja', 'file_review_sheet' => 'Review Sheet', 'file_nota_dinas' => 'Nota Dinas'] as $field => $title)
                <div id="uploader-{{ $field }}" class="bg-white/50 border rounded-xl p-6 hover:shadow-lg transition-shadow duration-300 uploader-container" data-field-name="{{ $field }}">
                    <h3 class="font-semibold text-[--color-dark] mb-4">{{ $title }}</h3>
                    @if($lhp->$field)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                            <a href="{{ Storage::url($lhp->$field) }}" target="_blank" class="flex items-center truncate text-sm font-medium text-green-800"><i class="fas fa-check-circle text-green-500 mr-3"></i><span class="truncate">{{ basename($lhp->$field) }}</span></a>
                            <button type="button" wire:click="deleteFile('{{ $field }}')" wire:confirm="Anda yakin?" class="ml-4 text-gray-500 hover:text-red-600 transition"><i class="fas fa-trash"></i></button>
                        </div>
                    @else
                        <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-[--color-accent] hover:bg-[--color-accent]/5 transition">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i><p class="text-gray-600 text-sm">Klik untuk unggah</p>
                        </div>
                        <div class="progress-container w-full bg-gray-200 rounded-full mt-3 hidden"><div class="progress-bar bg-[--color-primary] text-xs text-white text-center p-0.5 leading-none rounded-full" style="width: 0%">0%</div></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Temuan Tab -->
        <div x-show="activeTab === 'temuan'" x-transition.opacity.duration.500ms>
            <form wire:submit.prevent="saveTemuan" class="space-y-6">
                <h3 class="text-2xl font-bold text-[--color-dark]">Detail Temuan & Rekomendasi</h3>
                <div>
                    <label for="temuan" class="block text-sm font-medium text-[--color-dark] mb-1">Temuan</label>
                    <textarea id="temuan" wire:model="temuan" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[--color-accent] focus:border-[--color-accent]"></textarea>
                </div>
                <div>
                    <label for="rekomendasi" class="block text-sm font-medium text-[--color-dark] mb-1">Rincian Rekomendasi</label>
                    <textarea id="rekomendasi" wire:model="rincian_rekomendasi" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[--color-accent] focus:border-[--color-accent]"></textarea>
                </div>
                <div>
                    <label for="besaran" class="block text-sm font-medium text-[--color-dark] mb-1">Besaran Temuan (Rp)</label>
                    <input type="number" id="besaran" wire:model="besaran_temuan" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[--color-accent] focus:border-[--color-accent]" placeholder="0">
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-[#1B5E20] text-white rounded-xl font-semibold transition-all hover:bg-[#388E3C] hover:shadow-lg hover:scale-105">Simpan Temuan</button>
                </div>
            </form>
        </div>

        <!-- Tindak Lanjut Tab -->
        <div x-show="activeTab === 'tindak-lanjut'" x-transition.opacity.duration.500ms>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-[--color-dark]">Daftar Tindak Lanjut</h3>
                <button wire:click="openTindakLanjutModal" class="w-full md:w-auto px-6 py-3 bg-[#1B5E20] text-white rounded-xl font-semibold transition-all hover:bg-[#388E3C] hover:shadow-lg hover:scale-105"><i class="fas fa-plus mr-2"></i> Tambah</button>
            </div>
            <div class="space-y-4">
                @forelse($lhp->tindakLanjuts as $tl)
                    <div class="bg-white/80 rounded-lg p-4 flex items-start gap-4 hover:shadow-md transition-shadow">
                       <i class="fas fa-file-alt text-2xl text-[--color-primary] mt-1"></i>
                       <div class="flex-grow">
                           <p class="font-semibold text-[--color-dark]">{{ $tl->file_name }}</p>
                           <p class="text-sm text-gray-600">{{ $tl->description }}</p>
                           <p class="text-xs text-gray-500 mt-1">{{ $tl->created_at->translatedFormat('d F Y, H:i') }}</p>
                       </div>
                       <div class="flex gap-4 text-gray-500 text-lg">
                           <a href="{{ Storage::url($tl->file_path) }}" target="_blank" class="hover:text-[--color-primary] transition" title="Unduh"><i class="fas fa-download"></i></a>
                           <button wire:click="openTindakLanjutModal({{ $tl->id }})" class="hover:text-[--color-secondary] transition" title="Edit"><i class="fas fa-edit"></i></button>
                           <button wire:click="deleteTindakLanjut({{ $tl->id }})" wire:confirm="Anda yakin?" class="hover:text-[--color-accent] transition" title="Hapus"><i class="fas fa-trash"></i></button>
                       </div>
                    </div>
                @empty
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg"><i class="fas fa-folder-open fa-3x text-gray-400"></i><p class="mt-4 text-gray-600">Belum ada tindak lanjut.</p></div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Tindak Lanjut Modal -->
    <div x-show="$wire.showTindakLanjutModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="$wire.closeTindakLanjutModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="$wire.showTindakLanjutModal" x-transition.opacity></div>
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl p-8" x-show="$wire.showTindakLanjutModal" x-transition>
            <h3 class="text-2xl font-bold text-[--color-dark] mb-6">{{ $tindakLanjutId ? 'Edit' : 'Tambah' }} Tindak Lanjut</h3>
            <form wire:submit.prevent="saveTindakLanjut" class="space-y-4">
                <div>
                    <label class="font-medium text-gray-700">File Tindak Lanjut</label>
                    <input type="file" wire:model="tindakLanjutFile" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div wire:loading wire:target="tindakLanjutFile" class="text-sm text-[--color-accent] mt-1">Mengunggah...</div>
                    @if($editingTindakLanjut?->file_name && !$tindakLanjutFile) <p class="text-sm text-gray-500 mt-1">File saat ini: {{ $editingTindakLanjut->file_name }}</p> @endif
                    @error('tindakLanjutFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                 <div>
                    <label class="font-medium text-gray-700">Keterangan</label>
                    <textarea wire:model="tindakLanjutDescription" rows="3" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[--color-accent] focus:border-[--color-accent]"></textarea>
                 </div>
                 <div class="flex justify-end gap-4 pt-4">
                     <button type="button" @click="$wire.closeTindakLanjutModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">Batal</button>
                     <button type="submit" class="w-full md:w-auto px-6 py-3 bg-[#1B5E20] text-white rounded-xl font-semibold transition-all hover:bg-[#388E3C] hover:shadow-lg hover:scale-105">Simpan</button>
                 </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lhpId = @json($lhpId);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const uploadUrl = '{{ route("livewire.upload-file") }}';

        document.querySelectorAll('.uploader-container').forEach(container => {
            const fieldName = container.dataset.fieldName;
            const uploadArea = container.querySelector('.upload-area');
            if (!uploadArea) return;

            uploadArea.addEventListener('click', () => {
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = '.pdf';
                fileInput.onchange = (event) => {
                    const file = event.target.files[0];
                    if (file) {
                        uploadFile(file, container, fieldName);
                    }
                };
                fileInput.click();
            });
        });

        function uploadFile(file, container, fieldName) {
            const progressContainer = container.querySelector('.progress-container');
            const progressBar = container.querySelector('.progress-bar');
            
            progressContainer.style.display = 'block';
            progressBar.style.width = '0%';
            progressBar.textContent = '0%';

            const formData = new FormData();
            formData.append('file', file);
            formData.append('lhp_id', lhpId);
            formData.append('field_name', fieldName);
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', uploadUrl, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = (event) => {
                if (event.lengthComputable) {
                    const percentComplete = Math.round((event.loaded / event.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressBar.textContent = percentComplete + '%';
                }
            };

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    Livewire.dispatch('upload-completed');
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'File berhasil diunggah!', showConfirmButton: false, timer: 3000 });
                } else {
                    let errorMsg = `Error: ${xhr.statusText}`;
                    try { const response = JSON.parse(xhr.responseText); errorMsg = response.message || response.error || errorMsg; } catch (e) {}
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Upload Gagal', text: errorMsg, showConfirmButton: false, timer: 5000 });
                }
            };

            xhr.onerror = () => {
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Upload Gagal', text: 'Terjadi kesalahan jaringan.', showConfirmButton: false, timer: 5000 });
            };
            xhr.send(formData);
        }
    });
</script>
@endpush