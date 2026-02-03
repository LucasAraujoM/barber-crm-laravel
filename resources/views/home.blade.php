<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barber CRM</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body>
    <div class="flex bg-gray-100 min-h-screen">
        <x-sidebar />

        <div class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Gestión de Turnos 💈</h1>

            <div class="grid grid-cols-4 gap-4">
                <x-stat-card title="Turnos Hoy" value="8" />
                <x-stat-card title="Ingresos" value="$380" />
                <x-stat-card title="Horas Reservadas" value="7h" />
                <!-- <AddTurn /> -->
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <x-calendar />
                </div>
                <div class="col-span-1">
                    <x-schedule />
                    <div class="mt-4">
                        <x-clients />
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>