@props(['title'])

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
        class="w-full p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-all duration-200 hover:shadow-sm text-left">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                {{ $slot }}
            </div>
            <div class="ml-2">
                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                    :class="{ 'rotate-180': open }"></i>
            </div>
        </div>
    </button>

    <div x-show="open" x-collapse.duration.300ms
        class="mt-2 space-y-2 pl-4 border-l-2 border-gray-200 dark:border-gray-700 ml-3">
        {{ $slot }}
    </div>
</div>
