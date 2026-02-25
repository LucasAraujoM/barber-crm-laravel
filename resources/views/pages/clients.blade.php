@extends('layout.app')

@section('title', 'Clientes')

@section('content')
    <div class="space-y-6">

        {{-- ══════════════════════════════════════════════════════════
        TARJETAS DE MÉTRICAS
        ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            {{-- Total Clientes --}}
            <div class="metric-card group">
                <div class="metric-icon bg-indigo-100 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="metric-label">Total Clientes</p>
                    <p class="metric-value">{{ number_format($totalClients) }}</p>
                    <div class="metric-badge {{ $clientsGrowth >= 0 ? 'badge-up' : 'badge-down' }}">
                        <span>{{ $clientsGrowth >= 0 ? '▲' : '▼' }} {{ abs($clientsGrowth) }}%</span>
                        <span class="ml-1 text-gray-400 font-normal">vs mes anterior</span>
                    </div>
                </div>
            </div>

            {{-- Nuevos este mes --}}
            <div class="metric-card group">
                <div class="metric-icon bg-emerald-100 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <line x1="19" y1="8" x2="19" y2="14" />
                        <line x1="22" y1="11" x2="16" y2="11" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="metric-label">Nuevos este Mes</p>
                    <p class="metric-value">{{ $newThisMonth }}</p>
                    <div class="metric-sub">
                        <span class="text-gray-400">Mes anterior:</span>
                        <span class="font-semibold text-gray-600 ml-1">{{ $newLastMonth }}</span>
                    </div>
                </div>
            </div>

            {{-- Cortes este mes --}}
            <div class="metric-card group">
                <div class="metric-icon bg-amber-100 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="6" cy="6" r="3" />
                        <circle cx="6" cy="18" r="3" />
                        <line x1="20" y1="4" x2="8.12" y2="15.88" />
                        <line x1="14.47" y1="14.48" x2="20" y2="20" />
                        <line x1="8.12" y1="8.12" x2="12" y2="12" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="metric-label">Cortes este Mes</p>
                    <p class="metric-value">{{ $cutsThisMonth }}</p>
                    <div class="metric-badge {{ $cutsGrowth >= 0 ? 'badge-up' : 'badge-down' }}">
                        <span>{{ $cutsGrowth >= 0 ? '▲' : '▼' }} {{ abs($cutsGrowth) }}%</span>
                        <span class="ml-1 text-gray-400 font-normal">vs mes anterior</span>
                    </div>
                </div>
            </div>

            {{-- Promedio de cortes --}}
            <div class="metric-card group">
                <div class="metric-icon bg-rose-100 text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                        <line x1="2" y1="20" x2="22" y2="20" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="metric-label">Promedio Cortes / Cliente</p>
                    <p class="metric-value">{{ $avgCutsPerClient }}</p>
                    <div class="metric-sub">
                        <span class="text-gray-400">Activos este mes:</span>
                        <span class="font-semibold text-gray-600 ml-1">{{ $activeThisMonth }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
        GRÁFICO + COMPARATIVA RÁPIDA
        ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Gráfico de tendencia --}}
            <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Tendencia de Cortes y Clientes</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Últimos 6 meses</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-indigo-500 inline-block"></span> Cortes
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Nuevos clientes
                        </span>
                    </div>
                </div>
                <div class="relative h-52">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>

            {{-- Comparativa mes --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4">
                <h2 class="text-base font-bold text-gray-800">Comparativa Mensual</h2>

                @php
                    $comparisons = [
                        ['label' => 'Nuevos Clientes', 'current' => $newThisMonth, 'prev' => $newLastMonth, 'color' => 'indigo'],
                        ['label' => 'Cortes Realizados', 'current' => $cutsThisMonth, 'prev' => $cutsLastMonth, 'color' => 'amber'],
                        ['label' => 'Clientes Activos', 'current' => $activeThisMonth, 'prev' => $activeLastMonth, 'color' => 'emerald'],
                    ];
                @endphp

                @foreach($comparisons as $comp)
                    @php
                        $max = max($comp['current'], $comp['prev'], 1);
                        $pct = round(($comp['current'] / $max) * 100);
                        $prevPct = round(($comp['prev'] / $max) * 100);
                        $diff = $comp['current'] - $comp['prev'];
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-600">{{ $comp['label'] }}</span>
                            <span class="text-xs font-bold {{ $diff >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                            </span>
                        </div>
                        <div class="relative h-2 bg-gray-100 rounded-full overflow-hidden mb-1">
                            <div class="absolute top-0 left-0 h-full bg-{{ $comp['color'] }}-200 rounded-full transition-all"
                                style="width: {{ $prevPct }}%"></div>
                            <div class="absolute top-0 left-0 h-full bg-{{ $comp['color'] }}-500 rounded-full transition-all"
                                style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>Este mes: <strong class="text-gray-700">{{ $comp['current'] }}</strong></span>
                            <span>Anterior: <strong class="text-gray-700">{{ $comp['prev'] }}</strong></span>
                        </div>
                    </div>
                @endforeach

                {{-- Actividad --}}
                <div class="mt-auto pt-4 border-t border-gray-100">
                    @php
                        $actPct = $totalClients > 0 ? round(($activeThisMonth / $totalClients) * 100) : 0;
                    @endphp
                    <p class="text-xs text-gray-500 mb-1">Tasa de actividad este mes</p>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all"
                                style="width: {{ $actPct }}%"></div>
                        </div>
                        <span class="text-sm font-bold text-indigo-600">{{ $actPct }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
        TABLA DE CLIENTES CON FILTROS
        ══════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Barra de filtros --}}
            <div class="p-5 border-b border-gray-100">
                <form method="GET" action="{{ route('clients') }}" id="filterForm"
                    class="flex flex-wrap gap-3 items-center">

                    {{-- Búsqueda --}}
                    <div class="relative flex-1 min-w-[200px]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                            placeholder="Buscar por nombre, email o teléfono…"
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition">
                    </div>

                    {{-- Filtro de actividad --}}
                    <div class="flex gap-1 bg-gray-100 rounded-lg p-1">
                        @foreach(['all' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos', 'new' => 'Nuevos'] as $val => $label)
                                        <a href="{{ request()->fullUrlWithQuery(['filter' => $val === 'all' ? null : $val, 'page' => 1]) }}"
                                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all
                                                                                                                                    {{ (request('filter', 'all') === $val || ($val === 'all' && !request('filter')))
                            ? 'bg-white text-indigo-600 shadow-sm'
                            : 'text-gray-500 hover:text-gray-700' }}">
                                            {{ $label }}
                                        </a>
                        @endforeach
                    </div>

                    {{-- Ordenamiento --}}
                    <select name="sort" id="sort-select" onchange="document.getElementById('filterForm').submit()"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white text-gray-600">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Más recientes
                        </option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                        <option value="appointments_count" {{ request('sort') === 'appointments_count' ? 'selected' : '' }}>
                            Más cortes</option>
                    </select>

                    <input type="hidden" name="dir" value="{{ request('dir', 'desc') }}">
                    <input type="hidden" name="filter" value="{{ request('filter') }}">

                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Buscar
                    </button>

                    @if(request('search') || request('filter'))
                        <a href="{{ route('clients') }}"
                            class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg transition-colors">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3 text-left">Cliente</th>
                            <th class="px-6 py-3 text-left">Contacto</th>
                            <th class="px-6 py-3 text-center">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'appointments_count', 'dir' => request('sort') === 'appointments_count' && request('dir') === 'desc' ? 'asc' : 'desc']) }}"
                                    class="flex items-center justify-center gap-1 hover:text-indigo-600 transition-colors">
                                    Cortes
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M7 16V4m0 0L3 8m4-4 4 4M17 8v12m0 0 4-4m-4 4-4-4" />
                                    </svg>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left">Último Turno</th>
                            <th class="px-6 py-3 text-center">Estado</th>
                            <th class="px-6 py-3 text-left">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => request('sort') === 'created_at' && request('dir') === 'desc' ? 'asc' : 'desc']) }}"
                                    class="flex items-center gap-1 hover:text-indigo-600 transition-colors">
                                    Registrado
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M7 16V4m0 0L3 8m4-4 4 4M17 8v12m0 0 4-4m-4 4-4-4" />
                                    </svg>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($clients as $client)
                            @php
                                $lastAppt = $client->appointments->first();
                                $startMonth = now()->startOfMonth();
                                $isActive = $lastAppt && $lastAppt->date
                                    ? \Carbon\Carbon::parse($lastAppt->date)->gte($startMonth)
                                    : false;
                                $isNew = $client->created_at
                                    ? $client->created_at->gte($startMonth)
                                    : false;
                            @endphp
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                {{-- Avatar + Nombre --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                            style="background: {{ sprintf('hsl(%d, 65%%, 55%%)', crc32($client->name) % 360) }}">
                                            {{ strtoupper(substr($client->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $client->name }}</p>
                                            @if($client->notes)
                                                <p class="text-xs text-gray-400 truncate max-w-[160px]">{{ $client->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Contacto --}}
                                <td class="px-6 py-4 text-gray-500">
                                    <div class="flex flex-col gap-0.5">
                                        @if($client->email)
                                            <span class="flex items-center gap-1.5 text-xs">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                                </svg>
                                                {{ $client->email }}
                                            </span>
                                        @endif
                                        @if($client->phone)
                                            <span class="flex items-center gap-1.5 text-xs">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.8a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16z" />
                                                </svg>
                                                {{ $client->phone }}
                                            </span>
                                        @endif
                                        @if(!$client->email && !$client->phone)
                                            <span class="text-xs text-gray-300 italic">Sin contacto</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Cortes --}}
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                                                            {{ $client->appointments_count > 5 ? 'bg-indigo-100 text-indigo-700' : ($client->appointments_count > 0 ? 'bg-gray-100 text-gray-600' : 'bg-gray-50 text-gray-400') }}">
                                        {{ $client->appointments_count }}
                                    </span>
                                </td>

                                {{-- Último turno --}}
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    @if($lastAppt && $lastAppt->date)
                                        <span class="font-medium text-gray-700">
                                            {{ \Carbon\Carbon::parse($lastAppt->date)->format('d/m/Y') }}
                                        </span>
                                        <br>
                                        <span class="text-gray-400">
                                            {{ \Carbon\Carbon::parse($lastAppt->date)->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 italic">Sin turnos</span>
                                    @endif
                                </td>

                                {{-- Estado --}}
                                <td class="px-6 py-4 text-center">
                                    @if($isNew)
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Nuevo
                                        </span>
                                    @elseif($isActive)
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Activo
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                                        </span>
                                    @endif
                                </td>

                                {{-- Fecha registro --}}
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    {{ optional($client->created_at)->format('d/m/Y') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg class="w-12 h-12 text-gray-200" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                        <p class="text-sm font-medium">No se encontraron clientes</p>
                                        @if(request('search') || request('filter'))
                                            <a href="{{ route('clients') }}" class="text-indigo-500 text-xs hover:underline">Limpiar
                                                filtros</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($clients->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400">
                        Mostrando {{ $clients->firstItem() }}–{{ $clients->lastItem() }} de {{ $clients->total() }} clientes
                    </p>
                    <div class="flex items-center gap-1">
                        @if($clients->onFirstPage())
                            <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-100 rounded-lg cursor-not-allowed">←
                                Anterior</span>
                        @else
                            <a href="{{ $clients->previousPageUrl() }}"
                                class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">←
                                Anterior</a>
                        @endif

                        @foreach($clients->getUrlRange(max(1, $clients->currentPage() - 2), min($clients->lastPage(), $clients->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}" class="px-3 py-1.5 text-xs rounded-lg transition-colors
                                                                                                                {{ $page === $clients->currentPage()
                            ? 'bg-indigo-600 text-white font-semibold'
                            : 'text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                                    {{ $page }}
                                </a>
                        @endforeach

                        @if($clients->hasMorePages())
                            <a href="{{ $clients->nextPageUrl() }}"
                                class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Siguiente
                                →</a>
                        @else
                            <span
                                class="px-3 py-1.5 text-xs text-gray-300 border border-gray-100 rounded-lg cursor-not-allowed">Siguiente
                                →</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const labels = @json(array_column($chartData, 'label'));
            const cuts = @json(array_column($chartData, 'cuts'));
            const newCli = @json(array_column($chartData, 'clients'));

            const ctx = document.getElementById('trendsChart').getContext('2d');

            const gradientCuts = ctx.createLinearGradient(0, 0, 0, 200);
            gradientCuts.addColorStop(0, 'rgba(99,102,241,0.25)');
            gradientCuts.addColorStop(1, 'rgba(99,102,241,0)');

            const gradientCli = ctx.createLinearGradient(0, 0, 0, 200);
            gradientCli.addColorStop(0, 'rgba(52,211,153,0.2)');
            gradientCli.addColorStop(1, 'rgba(52,211,153,0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Cortes',
                            data: cuts,
                            borderColor: '#6366f1',
                            backgroundColor: gradientCuts,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#6366f1',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Nuevos Clientes',
                            data: newCli,
                            borderColor: '#34d399',
                            backgroundColor: gradientCli,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#34d399',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e1b4b',
                            titleColor: '#c7d2fe',
                            bodyColor: '#e0e7ff',
                            padding: 10,
                            cornerRadius: 8,
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af', font: { size: 11 } },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' },
                            ticks: { color: '#9ca3af', font: { size: 11 }, precision: 0 },
                        },
                    },
                },
            });
        })();
    </script>
@endsection