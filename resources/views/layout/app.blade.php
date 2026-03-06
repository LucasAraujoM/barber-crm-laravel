<!DOCTYPE html>
<html lang="es">

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
        }

        input,
        textarea,
        select {
            caret-color: #000000 !important;
            color: #f8fafc !important;
        }

        ::placeholder {
            color: #94a3b8 !important;
        }

        /* Custom scrollbar to save space and look nicer */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        [x-cloak] {
            display: none !important;
        }

        /* ── Sidebar CSS-driven state (no x-show flicker) ── */

        /* When sidebar is OPEN: show labels, show open-icon, hide closed-icon, hide tooltip */
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

        /* When sidebar is CLOSED (class on <html>): hide labels, swap icons, show tooltip */
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

        /* Pre-set width before Alpine hydrates to prevent layout shift */
        #main-sidebar {
            width: 16rem;
        }

        /* 64 = w-64 */
        .sidebar-closed #main-sidebar {
            width: 5rem;
        }

        /* 20 = w-20 */
    </style>

    <script>
        // Runs synchronously before render — prevents any flicker
        (function () {
            if (localStorage.getItem('sidebarOpen') === 'false') {
                document.documentElement.classList.add('sidebar-closed');
            }
        })();
    </script>

    @livewireStyles
</head>

<body class="bg-[#F5F7FA] text-[#1F2937] selection:bg-indigo-500 selection:text-white">
    <div class="flex h-screen overflow-hidden">
        <livewire:sidebar />

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#F5F7FA] backdrop-blur-xl">

            <!-- Top bar -->
            <header
                class="bg-white/80 backdrop-blur-md border-b border-[#E5E7EB] px-5 py-2.5 flex items-center justify-between sticky top-0 z-10 shrink-0 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.3)]">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span class="font-black text-[#1F2937] tracking-tight">💈 Barber CRM</span>
                    <span class="text-gray-500">/</span>
                    <span class="text-gray-500 font-bold">{{ $title ?? 'Panel' }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Fecha y hora -->
                    <div class="text-xs text-gray-500 hidden sm:block" x-data="{ time: '' }" x-init="
                        const update = () => { time = new Date().toLocaleString('es-AR', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }); };
                        update(); setInterval(update, 1000);
                    " x-text="time"></div>

                    <!-- Indicador de estado -->
                    <div
                        class="flex items-center gap-1.5 text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        En línea
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-4 md:p-5 space-y-4 overflow-y-auto custom-scrollbar">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Footer -->
            <footer
                class="px-5 py-3 border-t border-[#E5E7EB] text-[10px] font-bold text-gray-500 flex items-center justify-between shrink-0 bg-white">
                <span>Barber CRM © {{ date('Y') }}</span>
                <span>v1.0.0</span>
            </footer>
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')

    {{-- Toast global --}}
    <x-toast />
    @livewireScripts
</body>

</html>