<div class="space-y-8" x-data="{ activeTab: window.location.hash.substring(1) || 'susunan-tim' }" x-init="$watch('activeTab', value => window.location.hash = value)">

    <!-- Modern Hero Header -->
    <div
        class="relative bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/50 to-transparent"></div>
        <div class="relative px-8 py-12 md:px-12 md:py-16">
            <div class="flex flex-col lg:flex-row items-start justify-between gap-8">
                <div class="flex-1 text-white">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <i class="fas fa-file-alt text-white mr-2"></i>
                        <span class="text-sm font-medium">Detail LHP</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                        {{ $lhp->judul_lhp }}
                    </h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-emerald-100">
                        <div class="flex items-center">
                            <i class="fas fa-hashtag text-yellow-300 mr-3"></i>
                            <span class="font-medium">{{ $lhp->nomor_lhp }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt text-yellow-300 mr-3"></i>
                            <span>{{ $lhp->tanggal_lhp->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user-tie text-yellow-300 mr-3"></i>
                            <span>{{ $lhp->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clipboard-list text-yellow-300 mr-3"></i>
                            <span>{{ $lhp->temuans->count() }} Temuan</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('lhp.export-pdf', $lhp->id) }}" target="_blank"
                        class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        <i class="fas fa-print mr-2"></i>
                        Cetak PDF
                    </a>
                    <a href="{{ route('lhps') }}"
                        class="inline-flex items-center px-6 py-3 bg-white text-emerald-600 font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Tab Navigation -->
    <div
        class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 p-2">
        <div class="flex flex-wrap gap-2">
            <button @click="activeTab = 'susunan-tim'"
                :class="activeTab === 'susunan-tim' ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg' :
                    'text-gray-600 dark:text-gray-300 hover:bg-white/80 dark:hover:bg-gray-700/50'"
                class="flex-1 px-6 py-4 rounded-xl font-medium transition-all duration-300 flex items-center justify-center">
                <i class="fas fa-users mr-2"></i>
                <span>Susunan Tim</span>
            </button>
            <button @click="activeTab = 'dokumen'"
                :class="activeTab === 'dokumen' ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg' :
                    'text-gray-600 dark:text-gray-300 hover:bg-white/80 dark:hover:bg-gray-700/50'"
                class="flex-1 px-6 py-4 rounded-xl font-medium transition-all duration-300 flex items-center justify-center">
                <i class="fas fa-file-alt mr-2"></i>
                <span>Dokumen</span>
            </button>
            <button @click="activeTab = 'temuan'"
                :class="activeTab === 'temuan' ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg' :
                    'text-gray-600 dark:text-gray-300 hover:bg-white/80 dark:hover:bg-gray-700/50'"
                class="flex-1 px-6 py-4 rounded-xl font-medium transition-all duration-300 flex items-center justify-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                <span>Temuan</span>
            </button>
        </div>
    </div>

    <!-- Tab Content Container -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden min-h-[500px]">

        <!-- Susunan Tim Tab -->
        <div x-show="activeTab === 'susunan-tim'" x-transition.opacity.duration.500ms class="p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Susunan Tim Pemeriksa</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola anggota tim yang terlibat dalam pemeriksaan
                        LHP ini</p>
                </div>
                <div class="text-right">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                        {{ $lhp->tim->count() }} Anggota
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add Team Member Form -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl p-6 border border-emerald-200/50 dark:border-emerald-700/50">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-user-plus text-emerald-600 mr-2"></i>
                            Tambah Anggota Tim
                        </h4>
                        <form wire:submit.prevent="addTim" class="space-y-4">
                            <div>
                                <label for="newTimPegawaiId"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih
                                    Pegawai</label>
                                <select wire:model="newTimPegawaiId" id="newTimPegawaiId"
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200">
                                    <option value="">Pilih Pegawai</option>
                                    @foreach ($pegawaiOptions as $pegawai)
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                                    @endforeach
                                </select>
                                @error('newTimPegawaiId')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="newTimJabatanId"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan
                                    dalam Tim</label>
                                <select wire:model="newTimJabatanId" id="newTimJabatanId"
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($jabatanTimOptions as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                    @endforeach
                                </select>
                                @error('newTimJabatanId')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-4 focus:ring-emerald-500/25 transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-plus mr-2"></i>
                                Tambahkan ke Tim
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Team Member List -->
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        @forelse($lhp->tim as $anggota)
                            <div
                                class="bg-white dark:bg-gray-700/50 rounded-2xl p-6 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ substr($anggota->pegawai->nama, 0, 1) }}
                                        </div>
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $anggota->pegawai->nama }}</h5>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $anggota->jabatanTim->nama ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="removeTim('{{ $anggota->id }}')"
                                        wire:confirm="Anda yakin ingin menghapus anggota ini dari tim?"
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200"
                                        title="Hapus Anggota">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <div
                                    class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users-slash text-3xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada anggota tim
                                </h4>
                                <p class="text-gray-500 dark:text-gray-400">Tambahkan anggota tim untuk memulai
                                    pemeriksaan</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen Tab -->
        <div x-show="activeTab === 'dokumen'" x-transition.opacity.duration.500ms class="p-8">
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Dokumen LHP</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Upload dan kelola dokumen yang terkait dengan LHP ini
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach (['file_surat_tugas' => ['title' => 'Surat Tugas', 'icon' => 'fas fa-file-signature', 'color' => 'blue'], 'file_lhp' => ['title' => 'File LHP', 'icon' => 'fas fa-file-alt', 'color' => 'emerald'], 'file_kertas_kerja' => ['title' => 'Kertas Kerja', 'icon' => 'fas fa-file-invoice', 'color' => 'purple'], 'file_review_sheet' => ['title' => 'Review Sheet', 'icon' => 'fas fa-file-check', 'color' => 'orange'], 'file_nota_dinas' => ['title' => 'Nota Dinas', 'icon' => 'fas fa-file-contract', 'color' => 'red'], 'file_p2hp' => ['title' => 'P2HP', 'icon' => 'fas fa-file-medical', 'color' => 'teal']] as $field => $config)
                    <div id="uploader-{{ $field }}"
                        class="bg-white dark:bg-gray-700/50 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-6 hover:border-{{ $config['color'] }}-400 hover:bg-{{ $config['color'] }}-50 dark:hover:bg-{{ $config['color'] }}-900/20 transition-all duration-300 uploader-container"
                        data-field-name="{{ $field }}">

                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i
                                    class="{{ $config['icon'] }} text-2xl text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $config['title'] }}</h4>

                            @if ($lhp->$field)
                                <div
                                    class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-200 dark:border-green-700">
                                    <div class="flex items-center justify-center space-x-3">
                                        <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                                        <a href="{{ Storage::url($lhp->$field) }}" target="_blank"
                                            class="text-sm font-medium text-green-700 dark:text-green-300 hover:text-green-800 dark:hover:text-green-200 truncate max-w-[150px]"
                                            title="{{ basename($lhp->$field) }}">
                                            {{ Str::limit(basename($lhp->$field), 20) }}
                                        </a>
                                        <button type="button" wire:click="deleteFile('{{ $field }}')"
                                            wire:confirm="Anda yakin ingin menghapus file ini?"
                                            class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="upload-area cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Klik untuk upload file PDF
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">Maksimal 10MB</p>
                                </div>
                                <div
                                    class="progress-container w-full bg-gray-200 dark:bg-gray-600 rounded-full mt-4 hidden">
                                    <div class="progress-bar bg-{{ $config['color'] }}-600 text-xs text-white text-center p-1 leading-none rounded-full transition-all duration-300"
                                        style="width: 0%">0%</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Temuan Tab -->
        <div x-show="activeTab === 'temuan'" x-transition.opacity.duration.500ms class="p-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Temuan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola temuan, rekomendasi, dan tindak lanjut</p>
                </div>
                <button wire:click="openTemuanModal"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-4 focus:ring-emerald-500/25 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Temuan
                </button>
            </div>

            @if ($lhp->temuans->isNotEmpty())
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Filter berdasarkan temuan:
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($lhp->temuans as $temuan)
                            <button wire:click="filterByTemuan('{{ $temuan->id }}')"
                                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $selectedTemuanId === $temuan->id ? 'bg-emerald-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                {{ Str::limit($temuan->rincian, 30) }}
                            </button>
                        @endforeach
                        @if ($selectedTemuanId)
                            <button wire:click="filterByTemuan(null)"
                                class="px-4 py-2 bg-red-100 text-red-700 rounded-xl text-sm font-medium hover:bg-red-200 transition-all duration-200">
                                <i class="fas fa-times mr-1"></i>
                                Reset Filter
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            <div class="space-y-8">
                @forelse ($temuans as $temuan)
                    <div
                        class="bg-white dark:bg-gray-700/50 rounded-2xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <!-- Temuan Header -->
                        <div
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-sm font-bold">
                                            {{ $loop->iteration }}
                                        </span>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $temuan->jenis_pengawasan }}</h4>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $temuan->rincian }}
                                    </p>
                                    @if ($temuan->penyebab)
                                        <div
                                            class="mt-3 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-700">
                                            <p class="text-sm text-orange-800 dark:text-orange-300">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <strong>Penyebab:</strong> {{ $temuan->penyebab }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <button wire:click="openTemuanModal('{{ $temuan->id }}')"
                                        class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200"
                                        title="Edit Temuan">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="deleteTemuan('{{ $temuan->id }}')"
                                        wire:confirm="Anda yakin ingin menghapus temuan ini beserta semua rekomendasi dan tindak lanjutnya?"
                                        class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200"
                                        title="Hapus Temuan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Rekomendasi Section -->
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                    Rekomendasi ({{ $temuan->rekomendasis->count() }})
                                </h5>
                                <button wire:click="openRekomendasiModal('{{ $temuan->id }}')"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-all duration-200">
                                    <i class="fas fa-plus mr-1"></i>
                                    Tambah
                                </button>
                            </div>

                            @forelse ($temuan->rekomendasis as $rekomendasi)
                                <div x-data="{ expanded: true }" class="mb-6 last:mb-0">
                                    <div
                                        class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-700">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <p class="text-gray-800 dark:text-gray-200 leading-relaxed">
                                                    {{ $rekomendasi->rincian }}</p>
                                                @if ($rekomendasi->besaran_temuan > 0)
                                                    <div
                                                        class="mt-2 inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full text-sm font-medium">
                                                        <i class="fas fa-money-bill-wave mr-2"></i>
                                                        Rp
                                                        {{ number_format($rekomendasi->besaran_temuan, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <button
                                                    wire:click="openRekomendasiModal('{{ $temuan->id }}', '{{ $rekomendasi->id }}')"
                                                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-all duration-200">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </button>
                                                <button wire:click="deleteRekomendasi('{{ $rekomendasi->id }}')"
                                                    wire:confirm="Anda yakin ingin menghapus rekomendasi ini beserta semua tindak lanjutnya?"
                                                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition-all duration-200">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                                <button @click="expanded = !expanded"
                                                    class="p-1.5 text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-all duration-200">
                                                    <i class="fas text-sm"
                                                        :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Tindak Lanjut Section -->
                                        <div x-show="expanded" x-collapse
                                            class="border-t border-yellow-200 dark:border-yellow-700 pt-4 mt-4">
                                            <div class="flex justify-between items-center mb-3">
                                                <h6
                                                    class="font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                                    <i class="fas fa-tasks text-blue-500 mr-2"></i>
                                                    Tindak Lanjut ({{ $rekomendasi->tindakLanjuts->count() }})
                                                </h6>
                                                <button wire:click="openTindakLanjutModal('{{ $rekomendasi->id }}')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all duration-200 text-sm">
                                                    <i class="fas fa-plus mr-1"></i>
                                                    Tambah
                                                </button>
                                            </div>

                                            <div class="space-y-3">
                                                @forelse ($rekomendasi->tindakLanjuts as $tindakLanjut)
                                                    <div
                                                        class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex-1">
                                                                <p
                                                                    class="text-sm text-gray-800 dark:text-gray-200 mb-1">
                                                                    {{ $tindakLanjut->description ?: 'Tidak ada keterangan' }}
                                                                </p>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                    <i class="far fa-calendar mr-1"></i>
                                                                    {{ \Carbon\Carbon::parse($tindakLanjut->tanggal)->translatedFormat('d F Y') }}
                                                                </p>
                                                            </div>
                                                            <div class="flex items-center space-x-2 ml-4">
                                                                @if ($tindakLanjut->file_path)
                                                                    <button
                                                                        wire:click="openFilePreviewModal('{{ Storage::url($tindakLanjut->file_path) }}')"
                                                                        class="p-1.5 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/30 rounded transition-all duration-200"
                                                                        title="Lihat File">
                                                                        <i class="fas fa-eye text-sm"></i>
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    wire:click="openTindakLanjutModal('{{ $rekomendasi->id }}', '{{ $tindakLanjut->id }}')"
                                                                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-all duration-200"
                                                                    title="Edit">
                                                                    <i class="fas fa-edit text-sm"></i>
                                                                </button>
                                                                <button
                                                                    wire:click="deleteTindakLanjut('{{ $tindakLanjut->id }}')"
                                                                    wire:confirm="Anda yakin ingin menghapus tindak lanjut ini?"
                                                                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition-all duration-200"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                                        <i class="fas fa-inbox text-2xl mb-2"></i>
                                                        <p class="text-sm">Belum ada tindak lanjut</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div
                                        class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-lightbulb text-2xl text-yellow-500"></i>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada
                                        rekomendasi</h4>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Tambahkan rekomendasi untuk temuan
                                        ini</p>
                                    <button wire:click="openRekomendasiModal('{{ $temuan->id }}')"
                                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Rekomendasi
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div
                            class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-3xl text-gray-400"></i>
                        </div>
                        <h4 class="text-xl font-medium text-gray-900 dark:text-white mb-2">Belum ada temuan</h4>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai dengan menambahkan temuan pertama untuk
                            LHP ini</p>
                        <button wire:click="openTemuanModal"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Temuan Pertama
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modern Modals -->

    <!-- Temuan Modal -->
    <div x-data="{ show: @entangle('isTemuanModalOpen') }" x-show="show" @keydown.escape.window="show = false"
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
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $temuanId ? 'Edit Temuan' : 'Tambah Temuan Baru' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $temuanId ? 'Perbarui informasi temuan' : 'Buat temuan baru untuk LHP ini' }}
                        </p>
                    </div>
                    <button @click="show = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveTemuan">
                    <div class="space-y-6">
                        <div>
                            <label for="jenis_pengawasan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Pengawasan
                            </label>
                            <select wire:model="jenis_pengawasan" id="jenis_pengawasan"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200">
                                <option value="">Pilih Jenis Pengawasan</option>
                                @foreach (\App\Models\Temuan::$jenisPengawasanOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('jenis_pengawasan')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="rincian"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Rincian Temuan
                            </label>
                            <textarea wire:model="rincian" id="rincian" rows="4"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                                placeholder="Jelaskan detail temuan yang ditemukan..."></textarea>
                            @error('rincian')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="penyebab"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penyebab (Opsional)
                            </label>
                            <textarea wire:model="penyebab" id="penyebab" rows="3"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                                placeholder="Jelaskan penyebab dari temuan ini..."></textarea>
                            @error('penyebab')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="show = false"
                            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-xl transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>
                            {{ $temuanId ? 'Perbarui Temuan' : 'Simpan Temuan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Modal -->
    <div x-data="{ show: @entangle('isRekomendasiModalOpen') }" x-show="show" @keydown.escape.window="show = false"
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
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $rekomendasiId ? 'Edit Rekomendasi' : 'Tambah Rekomendasi Baru' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $rekomendasiId ? 'Perbarui rekomendasi' : 'Buat rekomendasi untuk temuan ini' }}
                        </p>
                    </div>
                    <button @click="show = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveRekomendasi">
                    <div class="space-y-6">
                        <div>
                            <label for="rincian_rekomendasi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Rincian Rekomendasi
                            </label>
                            <textarea wire:model="rincian_rekomendasi" id="rincian_rekomendasi" rows="4"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200"
                                placeholder="Jelaskan rekomendasi yang diberikan..."></textarea>
                            @error('rincian_rekomendasi')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="besaran_temuan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Besaran Temuan (Rupiah)
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                                <input type="number" wire:model="besaran_temuan" id="besaran_temuan"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200"
                                    placeholder="0" min="0" step="1">
                            </div>
                            @error('besaran_temuan')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="show = false"
                            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-xl transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>
                            {{ $rekomendasiId ? 'Perbarui Rekomendasi' : 'Simpan Rekomendasi' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tindak Lanjut Modal -->
    <div x-data="{ show: @entangle('isTindakLanjutModalOpen') }" x-show="show" @keydown.escape.window="show = false"
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
                            {{ $tindakLanjutId ? 'Edit Tindak Lanjut' : 'Tambah Tindak Lanjut Baru' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $tindakLanjutId ? 'Perbarui tindak lanjut' : 'Upload file dan keterangan tindak lanjut' }}
                        </p>
                    </div>
                    <button @click="show = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            File Tindak Lanjut
                        </label>
                        <div
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-300">
                            <input type="file" id="tindakLanjutFileInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all duration-200">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                Format yang didukung: PDF, DOC, DOCX, JPG, PNG (Maks. 10MB)
                            </p>
                        </div>
                        @if ($tindakLanjutId && ($tl = \App\Models\TindakLanjut::find($tindakLanjutId)) && $tl->file_path)
                            <div
                                class="mt-2 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                                <p class="text-sm text-green-700 dark:text-green-300">
                                    <i class="fas fa-file mr-2"></i>
                                    File saat ini: {{ $tl->file_name ?? basename($tl->file_path) }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterangan Tindak Lanjut
                        </label>
                        <textarea id="tindakLanjutDescriptionInput" wire:model.defer="tindakLanjutDescription" rows="4"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder="Jelaskan tindak lanjut yang telah dilakukan..."></textarea>
                    </div>

                    <div id="tindakLanjutProgressContainer"
                        class="w-full bg-gray-200 dark:bg-gray-600 rounded-full hidden">
                        <div id="tindakLanjutProgressBar"
                            class="bg-blue-600 text-xs text-white text-center p-1 leading-none rounded-full transition-all duration-300"
                            style="width: 0%">0%</div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="show = false"
                        class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-xl transition-colors duration-200">
                        Batal
                    </button>
                    <button type="button" id="saveTindakLanjutBtn"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 flex items-center justify-center min-w-[140px]">
                        <i class="fas fa-save mr-2"></i>
                        {{ $tindakLanjutId ? 'Perbarui' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div x-data="{ show: @entangle('isFilePreviewModalOpen') }" x-show="show" @keydown.escape.window="show = false"
        class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        style="display: none;">
        <div
            class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 relative">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="show = false"
                aria-hidden="true"></div>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-2xl shadow-2xl transform transition-all w-full max-w-6xl max-h-[90vh] mx-4">

                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Preview File</h3>
                    <button @click="show = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-4 overflow-auto" style="max-height: calc(90vh - 80px);">
                    @if ($previewFileUrl)
                        @if ($previewFileType === 'image')
                            <img src="{{ $previewFileUrl }}" alt="Preview"
                                class="max-w-full h-auto mx-auto rounded-lg shadow-lg">
                        @elseif($previewFileType === 'pdf')
                            <iframe src="{{ $previewFileUrl }}"
                                class="w-full rounded-lg border border-gray-200 dark:border-gray-600"
                                style="height: 80vh;"></iframe>
                        @else
                            <div class="text-center py-16">
                                <div
                                    class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-file text-3xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Preview tidak
                                    tersedia</h4>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">File ini tidak dapat dipratinjau di
                                    browser</p>
                                <a href="{{ $previewFileUrl }}" target="_blank"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200">
                                    <i class="fas fa-download mr-2"></i>
                                    Unduh File
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', function() {
            const lhpId = @json($lhpId);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const uploadUrl = '{{ route('lhp.upload-file') }}';

            // Document Upload Handlers
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

            // Tindak Lanjut Upload Handler
            const saveTindakLanjutBtn = document.getElementById('saveTindakLanjutBtn');
            if (saveTindakLanjutBtn) {
                saveTindakLanjutBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const fileInput = document.getElementById('tindakLanjutFileInput');
                    const descriptionInput = document.getElementById('tindakLanjutDescriptionInput');
                    const file = fileInput.files[0];
                    const description = descriptionInput.value;
                    const tindakLanjutId = @this.get('tindakLanjutId');
                    const rekomendasiId = @this.get('currentRekomendasiId');

                    if (!file && tindakLanjutId) {
                        @this.saveTindakLanjut();
                        return;
                    }
                    if (!file && !tindakLanjutId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Diperlukan',
                            text: 'Silakan pilih file untuk diunggah.',
                            confirmButtonColor: '#10b981'
                        });
                        return;
                    }

                    const container = document.getElementById('tindakLanjutProgressContainer')
                        .parentElement;
                    uploadFile(file, container, 'tindak_lanjut', {
                        description: description,
                        rekomendasi_id: rekomendasiId,
                        tindak_lanjut_id: tindakLanjutId,
                    });
                });
            }

            function uploadFile(file, container, fieldName, metadata = {}) {
                const progressContainer = container.querySelector(
                    '.progress-container, #tindakLanjutProgressContainer');
                const progressBar = container.querySelector('.progress-bar, #tindakLanjutProgressBar');

                if (progressContainer && progressBar) {
                    progressContainer.classList.remove('hidden');
                    progressBar.style.width = '0%';
                    progressBar.textContent = '0%';
                }

                const formData = new FormData();
                formData.append('file', file);
                formData.append('field_name', fieldName);

                if (fieldName === 'tindak_lanjut') {
                    formData.append('rekomendasi_id', metadata.rekomendasi_id);
                    if (metadata.tindak_lanjut_id) {
                        formData.append('tindak_lanjut_id', metadata.tindak_lanjut_id);
                    }
                    if (metadata.description) {
                        formData.append('description', metadata.description);
                    }
                } else {
                    formData.append('lhp_id', lhpId);
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', uploadUrl, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.onprogress = (event) => {
                    if (event.lengthComputable && progressBar) {
                        const percentComplete = Math.round((event.loaded / event.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                        progressBar.textContent = percentComplete + '%';
                    }
                };

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        Livewire.dispatch('upload-completed');
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'File berhasil diunggah!'
                        });
                        if (fieldName === 'tindak_lanjut') {
                            @this.closeTindakLanjutModal();
                        }
                    } else {
                        let errorMsg = `Error: ${xhr.statusText}`;
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMsg = response.message || response.error || errorMsg;
                        } catch (e) {}
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Gagal',
                            text: errorMsg,
                            confirmButtonColor: '#10b981'
                        });
                    }
                    if (progressContainer) {
                        progressContainer.classList.add('hidden');
                    }
                };

                xhr.onerror = () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Gagal',
                        text: 'Terjadi kesalahan jaringan.',
                        confirmButtonColor: '#10b981'
                    });
                    if (progressContainer) {
                        progressContainer.classList.add('hidden');
                    }
                };

                xhr.send(formData);
            }
        });
    </script>
@endpush
