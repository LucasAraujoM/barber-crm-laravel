<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel' }} — Barber CRM</title>
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-app);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ── Sidebar CSS-driven state ───────────────────────── */
        #main-sidebar .sidebar-label {
            opacity: 1;
            max-width: 200px;
        }

        #main-sidebar .sidebar-icon-open {
            display: block;
        }

        #main-sidebar .sidebar-icon-closed {
            display: none;
        }

        #main-sidebar .sidebar-tooltip {
            display: none;
        }

        .sidebar-closed #main-sidebar .sidebar-label {
            opacity: 0;
            max-width: 0;
            overflow: hidden;
        }

        .sidebar-closed #main-sidebar .sidebar-icon-open {
            display: none;
        }

        .sidebar-closed #main-sidebar .sidebar-icon-closed {
            display: block;
        }

        .sidebar-closed #main-sidebar .sidebar-tooltip {
            display: block;
        }

        #main-sidebar {
            width: 16rem;
        }

        .sidebar-closed #main-sidebar {
            width: 5rem;
        }

        /* ── Theme transition suave en todas las superficies ── */
        *,
        *::before,
        *::after {
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }

        /* Excepciones: no transición en animaciones de UI */
        .animate-ping,
        .animate-pulse {
            transition: none !important;
        }
    </style>

    <script>
        // Previene flicker del sidebar Y del tema — corre antes de renderizar
        (function () {
            // Sidebar
            if (localStorage.getItem('sidebarOpen') === 'false') {
                document.documentElement.classList.add('sidebar-closed');
            }
            // Tema dark/light
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    @livewireStyles
</head>

<body>
    <div class="flex h-screen overflow-hidden">
        <livewire:sidebar />

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden" style="background-color: var(--bg-app);">

            <!-- Top bar -->
            <header
                style="background-color: var(--bg-header); border-color: var(--border); backdrop-filter: blur(12px);"
                class="border-b px-5 py-2.5 flex items-center justify-between sticky top-0 z-10 shrink-0 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.15)]">

                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-xs" style="color: var(--text-secondary);">
                    <span class="font-black tracking-tight" style="color: var(--text-primary);">💈 Barber CRM</span>
                    <span>/</span>
                    <span class="font-bold">{{ $title ?? 'Panel' }}</span>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Fecha y hora -->
                    <div class="text-xs hidden sm:block" style="color: var(--text-secondary);" x-data="{ time: '' }"
                        x-init="
                            const update = () => { time = new Date().toLocaleString('es-AR', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }); };
                            update(); setInterval(update, 1000);
                        " x-text="time">
                    </div>

                    <!-- Toggle Dark / Light -->
                    <button id="theme-toggle" onclick="toggleTheme()" title="Cambiar tema"
                        style="background-color: var(--bg-surface-2); border-color: var(--border); color: var(--text-secondary);"
                        class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all hover:scale-105 active:scale-95 shrink-0">
                        <!-- Ícono Sol (light) -->
                        <svg id="icon-light" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5" />
                            <path
                                d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                        </svg>
                        <!-- Ícono Luna (dark) -->
                        <svg id="icon-dark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z" />
                        </svg>
                    </button>

                    <!-- Indicador online -->
                    <div
                        class="flex items-center gap-1.5 text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        En línea
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-4 md:p-5 overflow-hidden flex flex-col min-h-0">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Footer -->
            <footer style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-muted);"
                class="border-t px-5 py-3 text-[10px] font-bold flex items-center justify-between shrink-0">
                <span>Barber CRM © {{ date('Y') }}</span>
                <span>v1.0.0</span>
            </footer>
        </div>
    </div>

    <!-- Script de toggle de tema -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme') || 'light';
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeIcons(next);
        }

        function updateThemeIcons(theme) {
            const iconLight = document.getElementById('icon-light');
            const iconDark = document.getElementById('icon-dark');
            if (!iconLight || !iconDark) return;
            if (theme === 'dark') {
                iconLight.style.display = 'none';
                iconDark.style.display = 'block';
            } else {
                iconLight.style.display = 'block';
                iconDark.style.display = 'none';
            }
        }

        // Sincronizar íconos al cargar
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            // Pequeño delay para esperar que el DOM esté listo
            window.addEventListener('DOMContentLoaded', () => updateThemeIcons(theme));
        })();
    </script>

    @yield('scripts')
    @stack('scripts')

    {{-- Toast global --}}
    <x-toast />
    @livewireScripts
</body>

</html>