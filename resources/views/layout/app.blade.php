<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel' }} — {{ $companyName ?? 'Barber CRM' }}</title>
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="min-h-screen" x-data="themeApp()">
    <script>
        function themeApp() {
            return {
                init() {
                    const saved = localStorage.getItem('theme') || 'light';
                    document.documentElement.setAttribute('data-theme', saved);
                    if (localStorage.getItem('sidebarOpen') === 'false') {
                        document.documentElement.classList.add('sidebar-closed');
                    }
                },
                setTheme(theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                    localStorage.setItem('theme', theme);
                }
            };
        }
    </script>
    @livewireStyles
    <div class="drawer lg:drawer-open">
        <input id="sidebar-drawer" type="checkbox" class="drawer-toggle" checked />
        <div class="drawer-content flex flex-col min-h-screen">
            <header class="navbar bg-base-200/80 backdrop-blur-md border-b border-base-300 sticky top-0 z-10 px-4">
                <div class="flex-none lg:hidden">
                    <label for="sidebar-drawer" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path d="M3 12h18M3 6h18M3 18h18" />
                        </svg>
                    </label>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="font-black tracking-tight">{{ $companyName ?? 'Barber CRM' }}</span>
                        <span class="text-base-content/40">/</span>
                        <span class="font-bold">{{ $title ?? 'Panel' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="text-xs hidden sm:block text-base-content/60" x-data="{ time: '' }" x-init="
                            const update = () => { time = new Date().toLocaleString('es-AR', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }); };
                            update(); setInterval(update, 1000);
                        " x-text="time">
                    </div>

                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-sm gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="5" />
                                <path
                                    d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                            </svg>
                            <span class="text-xs">Tema</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[100] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a @click="setTheme('light')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="5" />
                                        <path
                                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                                    </svg>
                                    Claro
                                </a></li>
                            <li><a @click="setTheme('dark')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                                    </svg>
                                    Oscuro
                                </a></li>
                        </ul>
                    </div>

                    <div class="badge badge-success gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-success-content animate-pulse"></span>
                        En línea
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-6 flex-1 flex flex-col">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <footer
                class="footer footer-center p-4 bg-base-200 text-base-content border-t border-base-300 text-xs font-bold">
                <div>
                    <p>{{ $companyName ?? 'Barber CRM' }} © {{ date('Y') }} — v1.0.0</p>
                </div>
            </footer>
        </div>

        <div class="drawer-side z-40">
            <label for="sidebar-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <aside class="bg-base-200 min-h-screen w-64 shrink-0 flex flex-col">
                <div class="p-4 pb-2 flex items-center justify-between">
                    <h1 class="text-xl font-bold">{{ $companyName ?? 'Barber CRM' }}</h1>
                    <label for="sidebar-drawer" class="btn btn-square btn-ghost btn-sm lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </label>
                </div>
                <ul class="menu p-4 pt-0 flex-1 gap-2">
                    @foreach ($menuItems ?? [] as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" wire:navigate
                                class="{{ request()->routeIs($item['route']) ? 'bg-base-100 border-l-4' : '' }} font-bold text-xl py-4 gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    {!! $item['icon'] !!}
                                </svg>
                                {{ $item['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="p-4 border-t border-base-300">
                    <a href="{{ route('settings') }}" wire:navigate
                        class="btn btn-ghost w-full justify-start gap-3 text-base font-normal {{ request()->routeIs('settings') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                        Configuración
                    </a>
                </div>
            </aside>
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')

    <x-toast />
    @livewireScripts
</body>

</html>