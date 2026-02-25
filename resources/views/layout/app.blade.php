<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') — Barber CRM</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <!-- Alpine.js -->
    <script defer src="{{ asset('alpine.min.js') }}"></script>

    <!-- FilePond -->
    <link href="{{ asset('filepond.css') }}" rel="stylesheet" />
    <link href="{{ asset('filepond-plugin-image-preview.css') }}" rel="stylesheet" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <x-sidebar />

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top bar -->
            <header
                class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span class="font-semibold text-gray-800">💈 Barber CRM</span>
                    <span class="text-gray-300">/</span>
                    <span>@yield('title', 'Panel')</span>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Fecha y hora -->
                    <div class="text-xs text-gray-400 hidden sm:block" x-data="{ time: '' }" x-init="
                        const update = () => { time = new Date().toLocaleString('es-AR', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }); };
                        update(); setInterval(update, 1000);
                    " x-text="time"></div>

                    <!-- Indicador de estado -->
                    <div
                        class="flex items-center gap-1.5 text-xs text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        En línea
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-6 space-y-6 overflow-auto">
                {{-- Notificaciones flash --}}
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between mb-4 shadow-sm"
                        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0">
                        <div class="flex items-center gap-3 text-sm font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            {{ session('success') }}
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="px-6 py-3 border-t border-gray-100 text-xs text-gray-400 flex items-center justify-between">
                <span>Barber CRM © {{ date('Y') }}</span>
                <span>v1.0.0</span>
            </footer>
        </div>
    </div>

    <script src="{{ asset('filepond.js') }}"></script>
    <script src="{{ asset('filepond-plugin-image-preview.js') }}"></script>
    @yield('scripts')
</body>

</html>