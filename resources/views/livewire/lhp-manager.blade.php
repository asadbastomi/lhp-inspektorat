<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-[#263238]">Manajemen LHP</h1>
            <p class="text-gray-600 mt-1">Kelola semua Laporan Hasil Pemeriksaan di satu tempat.</p>
        </div>
        <button wire:click="create" class="w-full md:w-auto px-6 py-3 bg-[#1B5E20] text-white rounded-xl font-semibold transition-all hover:bg-[#388E3C] hover:shadow-lg hover:scale-105">
            <i class="fas fa-plus mr-2"></i> Tambah LHP Baru
        </button>
    </div>

    <div class="glass rounded-2xl p-4 mb-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative w-full md:flex-grow">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berdasarkan judul atau nomor LHP..." class="w-full pl-12 pr-4 py-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-[#1B5E20] focus:border-[#1B5E20] transition">
        </div>
        <div class="w-full md:w-auto flex-shrink-0">
            <select wire:model.live="statusFilter" class="w-full py-3 px-4 rounded-lg border-gray-300 focus:ring-2 focus:ring-[#1B5E20] focus:border-[#1B5E20] transition">
                <option value="">Semua Status</option>
                <option value="selesai">Selesai</option>
                <option value="proses">Dalam Proses</option>
            </select>
        </div>
    </div>
    
    <div class="glass rounded-2xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white/50">
                <tr>
                    @php
                        $columns = [
                            'judul_lhp' => 'Judul LHP',
                            'nomor_lhp' => 'Nomor LHP',
                            'user_id' => 'Irban',
                            'tanggal_lhp' => 'Tanggal',
                            'status' => 'Status',
                        ];
                    @endphp
                    @foreach($columns as $field => $label)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-[#263238] uppercase tracking-wider">
                        @if($field !== 'status')
                        <button wire:click="sortBy('{{ $field }}')" class="flex items-center gap-2">
                            {{ $label }}
                            @if($sortField === $field) <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                            @else <i class="fas fa-sort text-gray-400"></i> @endif
                        </button>
                        @else
                            {{ $label }}
                        @endif
                    </th>
                    @endforeach
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($lhps as $lhp)
                <tr wire:key="{{ $lhp->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-[#263238]">{{ Str::limit($lhp->judul_lhp, 40) }}</div></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lhp->nomor_lhp }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lhp->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lhp->tanggal_lhp->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($lhp->file_lhp)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-[#1B5E20]">Selesai</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#FBC02D]/20 text-[#263238]">Proses</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('lhp.detail', $lhp->id) }}" class="text-gray-500 hover:text-[#0277BD]" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                            <button wire:click="edit('{{ $lhp->id }}')" class="text-gray-500 hover:text-[#FBC02D]" title="Edit"><i class="fas fa-edit"></i></button>
                            <button wire:click="delete('{{ $lhp->id }}')" wire:confirm="Anda yakin ingin menghapus LHP ini?" class="text-gray-500 hover:text-[#C62828]" title="Hapus"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center"><div class="text-center"><i class="fas fa-folder-open fa-3x text-gray-400"></i><h3 class="mt-2 text-sm font-medium text-[#263238]">Tidak ada data ditemukan</h3><p class="mt-1 text-sm text-gray-500">Coba ubah filter atau tambahkan LHP baru.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $lhps->links() }}
    </div>

    <div x-data="{}" x-show="$wire.isModalOpen" x-on:keydown.escape.window="$wire.closeModal()" x-trap.inert.noscroll="$wire.isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div @click="$wire.closeModal()" class="fixed inset-0 bg-black/70 backdrop-blur-sm" x-show="$wire.isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
        <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl p-8" x-show="$wire.isModalOpen"
             x-init="$watch('$wire.isModalOpen', value => { if (value) { $nextTick(() => $refs.focusInput.focus()) } })"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-bold text-[#263238]">{{ $lhp_id ? 'Edit' : 'Tambah' }} LHP</h3>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600 transition text-2xl">&times;</button>
            </div>
            <form wire:submit.prevent="store" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="font-medium text-gray-700">Judul LHP</label>
                        <input type="text" wire:model="judul_lhp" x-ref="focusInput" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#388E3C] focus:border-[#388E3C]">
                        @error('judul_lhp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Irban Penanggung Jawab</label>
                        <select wire:model="user_id" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#388E3C] focus:border-[#388E3C]">
                            <option value="">Pilih Irban</option>
                            @foreach($irbans as $irban) <option value="{{ $irban->id }}">{{ $irban->name }}</option> @endforeach
                        </select>
                        @error('user_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Nomor LHP</label>
                        <input type="text" wire:model="nomor_lhp" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#388E3C] focus:border-[#388E3C]">
                        @error('nomor_lhp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Tanggal LHP</label>
                        <input type="date" wire:model="tanggal_lhp" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#388E3C] focus:border-[#388E3C]">
                        @error('tanggal_lhp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Nomor Surat Tugas</label>
                        <input type="text" wire:model="nomor_surat_tugas" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#388E3C] focus:border-[#388E3C]">
                        @error('nomor_surat_tugas') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Tanggal Penugasan</label>
                        <input type="date" wire:model="tanggal_penugasan" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#388E3C] focus:border-[#388E3C]">
                        @error('tanggal_penugasan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Lama Penugasan (Hari)</label>
                        <input type="number" wire:model="lama_penugasan" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#388E3C] focus:border-[#388E3C]">
                        @error('lama_penugasan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" wire:click="closeModal" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">Batal</button>
                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-2 bg-[#1B5E20] text-white rounded-lg font-medium transition-all hover:bg-[#388E3C] flex items-center min-w-[120px] justify-center">
                        <i class="fas fa-spinner animate-spin" wire:loading wire:target="store"></i>
                        <span wire:loading.remove wire:target="store">Simpan</span>
                        <span wire:loading wire:target="store">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>