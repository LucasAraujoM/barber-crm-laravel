<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js', 'public/filepond.css', 'public/filepond.js'])
    @endif
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- FilePond Plugins -->
    <link href="{{ asset('filepond.css') }}" rel="stylesheet" />
    <script src="{{ asset('filepond.js') }}"></script>
</head>

<body>
    <div class="flex bg-gray-100 min-h-screen">
        <x-sidebar />

        <div class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">@yield('title')</h1>

            @yield('content')
        </div>
    </div>
</body>

</html>