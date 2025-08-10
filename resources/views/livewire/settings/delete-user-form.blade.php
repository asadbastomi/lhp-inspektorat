<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Hapus Akun') }}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.') }}
        </p>
    </div>

    <div class="space-y-6">
        <div>
            <flux:button 
                variant="danger" 
                wire:click="confirmUserDeletion"
                wire:loading.attr="disabled"
                wire:target="confirmUserDeletion"
            >
                <span wire:loading.remove wire:target="confirmUserDeletion">
                    {{ __('Hapus Akun') }}
                </span>
                <span wire:loading wire:target="confirmUserDeletion">
                    {{ __('Memproses...') }}
                </span>
            </flux:button>
        </div>

        <div x-data="{ showPassword: false }" x-show="showPassword" x-transition>
            <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                    {{ __('Konfirmasi Penghapusan Akun') }}
                </h3>
                
                <p class="mt-2 text-sm text-red-700 dark:text-red-300">
                    {{ __('Untuk melanjutkan, harap masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                </p>

                <div class="mt-4">
                    <flux:input 
                        wire:model="password" 
                        :label="__('Password')" 
                        type="password" 
                        required 
                        autocomplete="current-password"
                        :error="$errors->first('password')"
                    />
                </div>

                <div class="mt-4 flex justify-end space-x-3 rtl:space-x-reverse">
                    <flux:button 
                        type="button" 
                        variant="secondary" 
                        @click="showPassword = false"
                        wire:loading.attr="disabled"
                        wire:target="deleteUser"
                    >
                        {{ __('Batal') }}
                    </flux:button>
                    
                    <flux:button 
                        type="button" 
                        variant="danger" 
                        wire:click="deleteUser"
                        wire:loading.attr="disabled"
                        wire:target="deleteUser"
                    >
                        <span wire:loading.remove wire:target="deleteUser">
                            {{ __('Hapus Akun') }}
                        </span>
                        <span wire:loading wire:target="deleteUser">
                            {{ __('Menghapus...') }}
                        </span>
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Add the delete script --}}
{!! $deleteScript ?? '' !!}
