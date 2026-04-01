<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel' }} — Barber CRM</title>
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @fluxAppearance
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">

    {{-- ═══════ Sidebar (desktop visible, mobile overlay) ═══════ --}}
    <flux:sidebar sticky collapsible class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand href="{{ route('home') }}" wire:navigate name="💈 Barber CRM" />
            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="squares-2x2" href="{{ route('home') }}" wire:navigate
                :current="request()->routeIs('home')">
                Panel
            </flux:sidebar.item>

            <flux:sidebar.item icon="calendar" href="{{ route('appointments.index') }}" wire:navigate
                :current="request()->routeIs('appointments.index')">
                Turnos
            </flux:sidebar.item>

            <flux:sidebar.item icon="users" href="{{ route('clients') }}" wire:navigate
                :current="request()->routeIs('clients')">
                Clientes
            </flux:sidebar.item>

            <flux:sidebar.item icon="scissors" href="{{ route('services.index') }}" wire:navigate
                :current="request()->routeIs('services.index')">
                Servicios
            </flux:sidebar.item>

            <flux:sidebar.item icon="user" href="{{ route('staff') }}" wire:navigate
                :current="request()->routeIs('staff')">
                Barberos
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="#">
                Configuración
            </flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>

    {{-- ═══════ Header mobile ═══════ --}}
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
            <span class="font-black tracking-tight text-zinc-800 dark:text-zinc-100">💈 Barber CRM</span>
            <span>/</span>
            <span class="font-bold">{{ $title ?? 'Panel' }}</span>
        </div>

        <flux:spacer />

        {{-- Toggle Dark / Light (mobile) --}}
        <button onclick="toggleTheme()" title="Cambiar tema"
            class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all hover:scale-105 active:scale-95 shrink-0 border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400">
            <svg class="theme-icon-light" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5" />
                <path
                    d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
            </svg>
            <svg class="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z" />
            </svg>
        </button>
    </flux:header>

    {{-- ═══════ Main content area ═══════ --}}
    <flux:main>
        {{-- Top bar (desktop only) --}}
        <header
            class="hidden lg:flex items-center justify-between -mx-6 -mt-6 mb-5 px-5 py-2.5 border-b border-zinc-200 dark:border-zinc-700 bg-white/85 dark:bg-zinc-900/90 backdrop-blur-md sticky top-0 z-10">
            {{-- Breadcrumb --}}
            <flux:breadcrumbs>
                <flux:breadcrumbs.item>💈 Barber CRM</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $title ?? 'Panel' }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>

            <div class="flex items-center gap-3">
                {{-- Fecha y hora --}}
                <div class="text-xs text-zinc-500 dark:text-zinc-400" x-data="{ time: '' }" x-init="
                        const update = () => { time = new Date().toLocaleString('es-AR', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }); };
                        update(); setInterval(update, 1000);
                    " x-text="time">
                </div>

                {{-- Toggle Dark / Light (desktop) --}}
                <button onclick="toggleTheme()" title="Cambiar tema"
                    class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all hover:scale-105 active:scale-95 shrink-0 border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-800">
                    <svg class="theme-icon-light" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5" />
                        <path
                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                    <svg class="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z" />
                    </svg>
                </button>

                {{-- Indicador online --}}
                <div
                    class="flex items-center gap-1.5 text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    En línea
                </div>
            </div>
        </header>

        {{-- Page content --}}
        {{ $slot ?? '' }}
        @yield('content')

        {{-- Footer --}}
        <footer
            class="border-t border-zinc-200 dark:border-zinc-700 px-5 py-3 text-[10px] font-bold flex items-center justify-between -mx-6 -mb-6 mt-6 text-zinc-400 dark:text-zinc-500 bg-zinc-50 dark:bg-zinc-900">
            <span>Barber CRM © {{ date('Y') }}</span>
            <span>v1.0.0</span>
        </footer>
    </flux:main>

    {{-- Script de toggle de tema --}}

    @yield('scripts')
    @stack('scripts')

    {{-- Toast global --}}
    <x-toast />
    @livewireScripts
    @fluxScripts
</body>

</html>