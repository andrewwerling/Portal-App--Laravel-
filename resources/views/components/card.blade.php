@props(['title', 'collapsed' => true])

<div x-data="{ isCollapsed: {{ $collapsed ? 'true' : 'false' }} }" 
     class="flex items-start gap-4 rounded-lg bg-white dark:bg-zinc-900 p-6 shadow">
    <div class="w-full">
        <div class="flex justify-between items-center">
            <div class="text-xl font-black text-zinc-900 dark:text-zinc-100">
                {{ $title }}
            </div>
            <button 
                @click="isCollapsed = !isCollapsed"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
            >
                <svg x-show="!isCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                <svg x-show="isCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
        <hr class="h-px mt-1 mb-3 bg-zinc-300 dark:bg-zinc-700 border-0">
        
        <div x-show="!isCollapsed" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2">
            {{ $slot }}
        </div>
    </div>
</div>
