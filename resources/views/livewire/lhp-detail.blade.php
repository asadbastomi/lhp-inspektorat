<div class="container mx-auto px-4 py-8" x-data="{ activeTab: window.location.hash.substring(1) || 'susunan-tim' }" x-init="$watch('activeTab', value => window.location.hash = value)">
    <!-- Header Card -->
    <div class="card mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#263238]">{{ $lhp->judul_lhp }}</h1>
                <div class="flex flex-wrap gap-x-4 gap-y-2 mt-4 text-sm">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#FBC02D]/20 text-[#263238]"><i class="fas fa-hashtag text-[#1B5E20] mr-2"></i>{{ $lhp->nomor_lhp }}</span>
                    <span class="flex items-center text-gray-700"><i class="far fa-calendar-alt text-[#0277BD] mr-2"></i>{{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}</span>
                    <span class="flex items-center text-gray-700"><i class="fas fa-user-tie text-[#0277BD] mr-2"></i>{{ $lhp->user->name ?? 'N/A' }}</span>
                </div>
            </div>
            <a href="{{ route('lhps') }}" class="btn-primary"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="glass rounded-t-2xl p-2 mb-0 shadow-md border-b">
        <div class="flex flex-wrap gap-2">
            <!-- NEW: Susunan Tim Tab -->
            <button @click="activeTab = 'susunan-tim'" :class="activeTab === 'susunan-tim' ? 'text-white bg-[#1B5E20] shadow-lg' : 'text-[#263238] hover:bg-white/80'" class="flex-1 px-6 py-3 rounded-xl font-medium transition-all"><i class="fas fa-users mr-2"></i> Susunan Tim</button>
            <button @click="activeTab = 'dokumen'" :class="activeTab === 'dokumen' ? 'text-white bg-[#1B5E20] shadow-lg' : 'text-[#263238] hover:bg-white/80'" class="flex-1 px-6 py-3 rounded-xl font-medium transition-all"><i class="fas fa-file-alt mr-2"></i> Dokumen</button>
            <button @click="activeTab = 'temuan'" :class="activeTab === 'temuan' ? 'text-white bg-[#1B5E20] shadow-lg' : 'text-[#263238] hover:bg-white/80'" class="flex-1 px-6 py-3 rounded-xl font-medium transition-all"><i class="fas fa-clipboard-list mr-2"></i> Temuan</button>
            <button @click="activeTab = 'tindak-lanjut'" :class="activeTab === 'tindak-lanjut' ? 'text-white bg-[#1B5E20] shadow-lg' : 'text-[#263238] hover:bg-white/80'" class="flex-1 px-6 py-3 rounded-xl font-medium transition-all"><i class="fas fa-tasks mr-2"></i> Tindak Lanjut <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $lhp->tindakLanjuts->count() }}</span></button>
        </div>
    </div>

    <!-- Tab Content Area -->
    <div class="card min-h-[400px] rounded-t-none">
        <!-- NEW: Susunan Tim Tab Content -->
        <div x-show="activeTab === 'susunan-tim'" x-transition.opacity.duration.500ms>
            <h3 class="text-2xl font-bold text-[#263238] mb-6">Susunan Tim Pemeriksa</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Add Team Member Form -->
                <div class="md:col-span-1">
                    <h4 class="font-semibold text-lg text-[#263238] mb-4">Tambah Anggota Tim</h4>
                    <form wire:submit.prevent="addTim" class="space-y-4">
                        <div>
                            <label for="newTimPegawaiId" class="block text-sm font-medium text-gray-700 mb-1">Pegawai</label>
                            <select wire:model="newTimPegawaiId" id="newTimPegawaiId" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]">
                                <option value="">Pilih Pegawai</option>
                                @foreach($pegawaiOptions as $pegawai)
                                    <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                                @endforeach
                            </select>
                            @error('newTimPegawaiId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="newTimJabatanId" class="block text-sm font-medium text-gray-700 mb-1">Jabatan dalam Tim</label>
                            <select wire:model="newTimJabatanId" id="newTimJabatanId" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]">
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatanTimOptions as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                @endforeach
                            </select>
                            @error('newTimJabatanId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn-primary w-full flex items-center justify-center">
                                <i class="fas fa-plus mr-2"></i> Tambahkan
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Team Member List -->
                <div class="md:col-span-2">
                    <h4 class="font-semibold text-lg text-[#263238] mb-4">Tim Saat Ini</h4>
                    <div class="space-y-3">
                        @forelse($lhp->tim as $anggota)
                        <div class="bg-white/80 rounded-lg p-3 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div>
                                <p class="font-semibold text-[#263238]">{{ $anggota->pegawai->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $anggota->jabatanTim->nama ?? 'N/A' }}</p>
                            </div>
                            <button wire:click="removeTim({{ $anggota->id }})" wire:confirm="Anda yakin ingin menghapus anggota ini dari tim?" class="text-gray-400 hover:text-[#C62828] transition" title="Hapus Anggota">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @empty
                        <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                            <i class="fas fa-users-slash fa-3x text-gray-400"></i>
                            <p class="mt-4 text-gray-600">Belum ada anggota tim yang ditambahkan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen Tab -->
        <div x-show="activeTab === 'dokumen'" x-transition.opacity.duration.500ms>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                 @foreach (['file_surat_tugas' => 'Surat Tugas', 'file_lhp' => 'File LHP', 'file_kertas_kerja' => 'Kertas Kerja', 'file_review_sheet' => 'Review Sheet', 'file_nota_dinas' => 'Nota Dinas'] as $field => $title)
                <div id="uploader-{{ $field }}" class="bg-white/50 border rounded-xl p-6 hover:shadow-lg transition-shadow duration-300 uploader-container" data-field-name="{{ $field }}">
                    <h3 class="font-semibold text-[#263238] mb-4">{{ $title }}</h3>
                    @if($lhp->$field)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                            <a href="{{ Storage::url($lhp->$field) }}" target="_blank" class="flex items-center truncate text-sm font-medium text-[#1B5E20]">
                                <i class="fas fa-check-circle mr-3"></i><span class="truncate">{{ basename($lhp->$field) }}</span>
                            </a>
                            <button type="button" wire:click="deleteFile('{{ $field }}')" wire:confirm="Anda yakin?" class="ml-4 text-gray-500 hover:text-[#C62828] transition"><i class="fas fa-trash"></i></button>
                        </div>
                    @else
                        <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-[#1B5E20] hover:bg-[#1B5E20]/5 transition">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i><p class="text-gray-600 text-sm">Klik untuk unggah PDF</p>
                        </div>
                        <div class="progress-container w-full bg-gray-200 rounded-full mt-3 hidden"><div class="progress-bar bg-[#1B5E20] text-xs text-white text-center p-0.5 leading-none rounded-full" style="width: 0%">0%</div></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Temuan Tab -->
        <div x-show="activeTab === 'temuan'" x-transition.opacity.duration.500ms>
            <form wire:submit.prevent="saveTemuan" class="space-y-6">
                <h3 class="text-2xl font-bold text-[#263238]">Detail Temuan & Rekomendasi</h3>
                <div>
                    <label for="temuan" class="block text-sm font-medium text-[#263238] mb-1">Temuan</label>
                    <textarea id="temuan" wire:model="temuan" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]"></textarea>
                </div>
                <div>
                    <label for="rekomendasi" class="block text-sm font-medium text-[#263238] mb-1">Rincian Rekomendasi</label>
                    <textarea id="rekomendasi" wire:model="rincian_rekomendasi" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]"></textarea>
                </div>
                <div>
                    <label for="besaran" class="block text-sm font-medium text-[#263238] mb-1">Besaran Temuan (Rp)</label>
                    <input type="number" id="besaran" wire:model="besaran_temuan" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]" placeholder="0">
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="btn-primary">Simpan Temuan</button>
                </div>
            </form>
        </div>

        <!-- Tindak Lanjut Tab -->
        <div x-show="activeTab === 'tindak-lanjut'" x-transition.opacity.duration.500ms>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-[#263238]">Daftar Tindak Lanjut</h3>
                <button wire:click="openTindakLanjutModal" class="btn-primary"><i class="fas fa-plus mr-2"></i> Tambah</button>
            </div>
            <div class="space-y-4">
                @forelse($lhp->tindakLanjuts as $tl)
                    <div class="bg-white/80 rounded-lg p-4 flex flex-col gap-4 hover:shadow-md transition-shadow">
                       <div class="flex items-start gap-4">
                            <div class="text-2xl text-[#0277BD] mt-1 w-8 text-center">
                                @if($tl->file_type == 'image') <i class="fas fa-file-image"></i>
                                @elseif($tl->file_type == 'video') <i class="fas fa-file-video"></i>
                                @elseif($tl->file_type == 'audio') <i class="fas fa-file-audio"></i>
                                @elseif($tl->file_type == 'pdf') <i class="fas fa-file-pdf"></i>
                                @else <i class="fas fa-file-alt"></i> @endif
                            </div>
                           <div class="flex-grow">
                               <p class="font-semibold text-[#263238]">{{ $tl->file_name }}</p>
                               <p class="text-sm text-gray-600">{{ $tl->description }}</p>
                               <p class="text-xs text-gray-500 mt-1">{{ $tl->created_at->translatedFormat('d F Y, H:i') }}</p>
                           </div>
                           <div class="flex gap-4 text-gray-500 text-lg">
                               <a href="{{ Storage::url($tl->file_path) }}" target="_blank" class="hover:text-[#0277BD] transition" title="Unduh"><i class="fas fa-download"></i></a>
                               <button wire:click="openTindakLanjutModal('{{ $tl->id }}')" class="hover:text-[#FBC02D] transition" title="Edit"><i class="fas fa-edit"></i></button>
                               <button wire:click="deleteTindakLanjut('{{ $tl->id }}')" wire:confirm="Anda yakin?" class="hover:text-[#C62828] transition" title="Hapus"><i class="fas fa-trash"></i></button>
                           </div>
                       </div>
                       <div class="pl-12">
                           @if($tl->file_type == 'image')
                               <img src="{{ Storage::url($tl->file_path) }}" alt="Preview" class="max-w-full md:max-w-md h-auto rounded-lg border shadow-sm">
                           @elseif($tl->file_type == 'video')
                               <video controls class="max-w-full md:max-w-md h-auto rounded-lg border shadow-sm"><source src="{{ Storage::url($tl->file_path) }}" type="{{ $tl->mime_type }}">Browser Anda tidak mendukung tag video.</video>
                           @elseif($tl->file_type == 'audio')
                               <audio controls class="w-full md:max-w-md"><source src="{{ Storage::url($tl->file_path) }}" type="{{ $tl->mime_type }}">Browser Anda tidak mendukung tag audio.</audio>
                           @elseif($tl->file_type == 'pdf')
                               <iframe src="{{ Storage::url($tl->file_path) }}" class="w-full h-96 rounded-lg border shadow-sm"></iframe>
                           @endif
                       </div>
                    </div>
                @empty
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg"><i class="fas fa-folder-open fa-3x text-gray-400"></i><p class="mt-4 text-gray-600">Belum ada tindak lanjut.</p></div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Tindak Lanjut Modal -->
    <div x-data="{ show: @entangle('showTindakLanjutModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="show = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="show" x-transition.opacity></div>
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl p-8" x-show="show" x-transition>
            <h3 class="text-2xl font-bold text-[#263238] mb-6">{{ $tindakLanjutId ? 'Edit' : 'Tambah' }} Tindak Lanjut</h3>
            <div class="space-y-4">
                <div>
                    <label class="font-medium text-gray-700">File Tindak Lanjut</label>
                    <input type="file" id="tindakLanjutFileInput" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    @if($editingTindakLanjut?->file_name) <p class="text-sm text-gray-500 mt-1">File saat ini: {{ $editingTindakLanjut->file_name }}</p> @endif
                </div>
                 <div>
                    <label class="font-medium text-gray-700">Keterangan</label>
                    <textarea id="tindakLanjutDescriptionInput" wire:model.defer="tindakLanjutDescription" rows="3" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]"></textarea>
                 </div>
                 <div id="tindakLanjutProgressContainer" class="w-full bg-gray-200 rounded-full mt-3 hidden"><div id="tindakLanjutProgressBar" class="bg-[#1B5E20] text-xs text-white text-center p-0.5 leading-none rounded-full" style="width: 0%">0%</div></div>
                 <div class="flex justify-end gap-4 pt-4">
                     <button type="button" @click="show = false" class="btn-secondary">Batal</button>
                     <button type="button" id="saveTindakLanjutBtn" class="btn-primary flex items-center justify-center min-w-[120px]">Simpan</button>
                 </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lhpId = @json($lhpId);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const uploadUrl = '{{ route("livewire.upload-file") }}';

    // --- Uploader for DOKUMEN TAB ---
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

    // --- Uploader for TINDAK LANJUT MODAL ---
    const saveTindakLanjutBtn = document.getElementById('saveTindakLanjutBtn');
    if (saveTindakLanjutBtn) {
        saveTindakLanjutBtn.addEventListener('click', () => {
            const fileInput = document.getElementById('tindakLanjutFileInput');
            const descriptionInput = document.getElementById('tindakLanjutDescriptionInput');
            const file = fileInput.files[0];
            const description = descriptionInput.value;
            
            const tindakLanjutId = @this.get('tindakLanjutId');

            if (!file && tindakLanjutId) {
                @this.saveTindakLanjut();
                return;
            }

            if (!file && !tindakLanjutId) {
                Swal.fire({ icon: 'error', title: 'File Diperlukan', text: 'Silakan pilih file untuk diunggah.' });
                return;
            }

            const container = document.getElementById('tindakLanjutProgressContainer').parentElement;
            uploadFile(file, container, 'tindak_lanjut', description, tindakLanjutId);
        });
    }

    function uploadFile(file, container, fieldName, description = null, tindakLanjutId = null) {
        const progressContainer = container.querySelector('.progress-container, #tindakLanjutProgressContainer');
        const progressBar = container.querySelector('.progress-bar, #tindakLanjutProgressBar');
        
        progressContainer.style.display = 'block';
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';

        const formData = new FormData();
        formData.append('file', file);
        formData.append('lhp_id', lhpId);
        formData.append('field_name', fieldName);
        if (description !== null) {
            formData.append('description', description);
        }
        if (tindakLanjutId !== null) {
            formData.append('tindak_lanjut_id', tindakLanjutId);
        }
        
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
                if (fieldName === 'tindak_lanjut') {
                    @this.closeTindakLanjutModal();
                }
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
