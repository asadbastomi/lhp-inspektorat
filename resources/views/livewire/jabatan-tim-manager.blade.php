<div class="container mx-auto px-4 py-8">
    <div class="card mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[--color-dark]">Manajemen Jabatan</h1>
                <p class="text-gray-600 mt-1">Kelola semua data Jabatan.</p>
            </div>
            <button wire:click="create" class="btn btn-primary w-full md:w-auto">
                <i class="fas fa-plus mr-2"></i> Tambah Jabatan Tim
            </button>
        </div>
    </div>

    <div class="mb-6">
        <div class="relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama Jabatan..."
                class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-300 shadow-sm focus:ring-[--color-accent] focus:border-[--color-accent] transition">
        </div>
    </div>

    <div class="card overflow-x-auto p-0">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/50">
                <tr>
                    @php
                        $columns = ['jabatan' => 'Nama Jabatan'];
                    @endphp
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-semibold text-[--color-dark] uppercase tracking-wider">
                        No</th>
                    @foreach ($columns as $field => $label)
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-[--color-dark] uppercase tracking-wider">
                            <button wire:click="sortBy('{{ $field }}')" class="flex items-center gap-2">
                                {{ $label }}
                                @if ($sortField === $field)
                                    <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400"></i>
                                @endif
                            </button>
                        </th>
                    @endforeach
                    <th scope="col" class="relative px-6 py-4"><span class="sr-only">Aksi</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($jabatans as $jabatan)
                    <tr wire:key="{{ $jabatan->id }}" class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-[--color-dark]">{{ $jabatan->jabatan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-4">
                                <button wire:click="edit('{{ $jabatan->id }}')"
                                    class="text-gray-500 hover:text-[--color-secondary] transition" title="Edit"><i
                                        class="fas fa-edit"></i></button>
                                <button wire:click="delete('{{ $jabatan->id }}')"
                                    wire:confirm="Anda yakin ingin menghapus data Jabatan ini?"
                                    class="text-gray-500 hover:text-[--color-accent] transition" title="Hapus"><i
                                        class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-center">
                                <i class="fas fa-users-slash fa-3x text-gray-400"></i>
                                <h3 class="mt-2 text-sm font-medium text-[--color-dark]">Tidak ada data Jabatan
                                    ditemukan</h3>
                                <p class="mt-1 text-sm text-gray-500">Silakan tambahkan data Jabatan baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $jabatan_tim->links() }}
    </div>

    <div x-show="$wire.isModalOpen" x-on:keydown.escape.window="$wire.closeModal()"
        x-trap.inert.noscroll="$wire.isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-cloak>
        <div @click="$wire.closeModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="$wire.isModalOpen"
            x-transition.opacity></div>
        <div class="card relative w-full max-w-lg bg-white p-8" x-show="$wire.isModalOpen" x-init="$watch('$wire.isModalOpen', value => { if (value) { $nextTick(() => $refs.focusInput.focus()) } })"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <h3 class="text-2xl font-bold text-[--color-dark] mb-6">{{ $jabatan_tim_id ? 'Edit' : 'Tambah' }} Data
                Jabatan Tim</h3>
            <form wire:submit.prevent="store" class="space-y-6">
                <div>
                    <label for="jabatan_tim" class="block text-sm font-medium text-[--color-dark] mb-1">Nama Jabatan
                        Tim</label>
                    <input type="text" id="jabatan_tim" wire:model="jabatan_tim" x-ref="focusInput"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[--color-accent] focus:border-[--color-accent]">
                    @error('jabatan_tim')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" @click="$wire.closeModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="btn btn-primary flex items-center justify-center min-w-[120px]">
                        <i class="fas fa-spinner animate-spin" wire:loading wire:target="store"></i>
                        <span wire:loading.remove wire:target="store">Simpan</span>
                        <span wire:loading wire:target="store">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
