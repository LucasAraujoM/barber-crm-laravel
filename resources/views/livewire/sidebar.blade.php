<aside id="main-sidebar" wire:persist="sidebar" x-data="{ isOpen: localStorage.getItem('sidebarOpen') !== 'false' }"
    x-init="
        document.documentElement.classList.toggle('sidebar-closed', !isOpen);
        $watch('isOpen', function(value) {
            localStorage.setItem('sidebarOpen', value);
            document.documentElement.classList.toggle('sidebar-closed', !value);
        });
    " :class="isOpen ? 'w-64' : 'w-20'" style="background-color: var(--bg-sidebar); border-color: var(--border);"
    class="h-screen sticky top-0 p-4 transition-all duration-300 flex flex-col border-r shrink-0">

    <!-- Header / Logo -->
    <div class="flex items-center justify-between mb-8 h-10">
        <div x-show="isOpen" x-cloak class="transition-opacity duration-300">
            <h1 class="text-xl font-bold whitespace-nowrap overflow-hidden text-white">
                💈 Barber CRM
            </h1>
        </div>

        <button @click="isOpen = !isOpen" :class="!isOpen ? 'mx-auto' : ''"
            class="p-2 hover:bg-white/10 text-slate-400 hover:text-white rounded-lg transition-colors focus:outline-none">
            <!-- Chevron Icons -->
            <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>
            <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1">
        <ul class="space-y-2">
            @foreach ($menuItems as $item)
                <li class="relative group">
                    <a href="{{ route($item['route']) }}" wire:navigate
                        class="{{ request()->routeIs($item['route']) ? 'bg-[#3B82F6] text-white' : 'text-slate-400 hover:bg-white/10 hover:text-white' }} flex items-center gap-4 p-3 rounded-lg cursor-pointer transition-colors">
                        <!-- Icon -->
                        <svg class="shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            {!! $item['icon'] !!}
                        </svg>

                        <!-- Label -->
                        <span x-show="isOpen" x-cloak
                            class="font-medium whitespace-nowrap overflow-hidden transition-opacity duration-300">{{ $item['name'] }}</span>
                    </a>

                    <!-- Tooltip cuando está cerrado -->
                    <div x-show="!isOpen"
                        style="background-color: var(--bg-surface); color: var(--text-primary); border-color: var(--border);"
                        class="absolute left-full top-1.5 ml-6 px-2 py-1 text-sm rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50 shadow-lg border">
                        {{ $item['name'] }}
                    </div>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>