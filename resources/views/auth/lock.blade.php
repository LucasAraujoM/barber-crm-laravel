<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloqueado — Barber CRM</title>
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: ui-sans-serif, system-ui, sans-serif;
        }
    </style>
    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    </script>
</head>

<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">
    <div class="card bg-base-100 shadow-2xl w-full max-w-sm">
        <div class="card-body items-center text-center p-8">
            <div class="text-5xl mb-4">🔒</div>
            <h2 class="text-xl font-black">App Bloqueada</h2>
            <p class="text-sm text-base-content/50 mt-1">Ingresá la contraseña para continuar</p>
            <form action="{{ route('unlock') }}" method="POST" class="w-full mt-4 space-y-3" onsubmit="this.querySelector('button').innerHTML='<span class=\'loading loading-spinner loading-sm\'></span> Verificando...'; this.querySelector('button').classList.add('btn-disabled');">
                @csrf
                <input type="password" name="password" placeholder="Contraseña" class="input input-bordered w-full"
                    required autofocus>
                @if(session('error'))
                    <p class="text-error text-sm font-bold">{{ session('error') }}</p>
                @endif
                <button type="submit" class="btn btn-primary w-full">
                    <span>Desbloquear</span>
                </button>
            </form>
            <p class="text-xs text-base-content/50 mt-4 opacity-80">Si olvidaste la contraseña, contactá a
                lucasgabrielaraujo13.08@gmail.com
            </p>
        </div>
    </div>
</body>

</html>