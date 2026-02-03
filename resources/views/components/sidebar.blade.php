@php
    $menuItems = [
        [
            'name' => 'Panel',
            'icon' => '
    <path d="M3 3h7v9H3z" />
    <path d="M14 3h7v5h-7z" />
    <path d="M14 12h7v9h-7z" />
    <path d="M3 16h7v5H3z" />', // LayoutDashboard
            'route' => 'home' // Placeholder
        ],
        [
            'name' => 'Turnos',
            'icon' => '
    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
    <line x1="16" y1="2" x2="16" y2="6" />
    <line x1="8" y1="2" x2="8" y2="6" />
    <line x1="3" y1="10" x2="21" y2="10" />', // Calendar
            'route' => 'home'
        ],
        [
            'name' => 'Clientes',
            'icon' => '
    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
    <circle cx="9" cy="7" r="4" />
    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
    <path d="M16 3.13a4 4 0 0 1 0 7.75" />', // Users
            'route' => 'home'
        ],
        [
            'name' => 'Servicios',
            'icon' => '
    <circle cx="6" cy="6" r="3" />
    <circle cx="6" cy="18" r="3" />
    <line x1="20" y1="4" x2="8.12" y2="15.88" />
    <line x1="14.47" y1="14.48" x2="20" y2="20" />
    <line x1="8.12" y1="8.12" x2="12" y2="12" />', // Scissors
            'route' => 'home'
        ],
        [
            'name' => 'Barberos',
            'icon' => '
    <circle cx="12" cy="8" r="5" />
    <path d="M20 21a8 8 0 1 0-16 0" />', // UserRound (approximated as User)
            'route' => 'staff'
        ],
    ];
@endphp

<aside x-data="{ isOpen: false }" :class="isOpen ? 'w-64' : 'w-20'"
    class="bg-gray-900 text-white min-h-screen p-4 transition-all duration-300 flex flex-col border-r border-gray-800">
    <!-- Header / Logo -->
    <div class="flex items-center justify-between mb-8 h-10">
        <template x-if="isOpen">
            <h1 class="text-xl font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300">
                💈 Barber CRM
            </h1>
        </template>

        <button @click="isOpen = !isOpen" :class="!isOpen ? 'mx-auto' : ''"
            class="p-2 hover:bg-gray-800 rounded-lg transition-colors focus:outline-none">
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
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-4 p-3 hover:bg-gray-800 rounded-lg cursor-pointer transition-colors">
                        <!-- Icon -->
                        <svg class="shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            {!! $item['icon'] !!}
                        </svg>

                        <!-- Label (Visible when open) -->
                        <template x-if="isOpen">
                            <span class="font-medium whitespace-nowrap overflow-hidden">{{ $item['name'] }}</span>
                        </template>
                    </a>

                    <!-- Tooltip (Visible when closed) -->
                    <div x-show="!isOpen"
                        class="absolute left-full top-1.5 ml-6 px-2 py-1 bg-gray-800 text-sm rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50 shadow-lg">
                        {{ $item['name'] }}
                    </div>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>