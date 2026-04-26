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
    <style>body { font-family: ui-sans-serif, system-ui, sans-serif; }</style>
    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    </script>
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4" x-data="{ password: '', error: false, loading: false }">
    <div class="card bg-base-100 shadow-2xl w-full max-w-sm">
        <div class="card-body items-center text-center p-8">
            <div class="text-5xl mb-4">🔒</div>
            <h2 class="text-xl font-black">App Bloqueada</h2>
            <p class="text-sm text-base-content/50 mt-1">Ingresá la contraseña para continuar</p>
            <form @submit.prevent="
                loading = true; error = false;
                fetch('/unlock?p=' + encodeURIComponent(password))
                    .then(r => r.json())
                    .then(d => { if(d.success) location.reload(); else { error = true; loading = false; } })
                    .catch(() => { error = true; loading = false; });
            " class="w-full mt-4 space-y-3">
                <input type="password" x-model="password" placeholder="Contraseña" class="input input-bordered w-full" required>
                <p x-show="error" class="text-error text-sm font-bold">Contraseña incorrecta</p>
                <button type="submit" class="btn btn-primary w-full" :class="loading && 'btn-disabled'">
                    <span x-show="!loading">Desbloquear</span>
                    <span x-show="loading" class="loading loading-spinner loading-sm">Verificando...</span>
                </button>
            </form>
        </div>
    </div>
</body>
</html>