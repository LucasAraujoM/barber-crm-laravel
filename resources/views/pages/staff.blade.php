@extends('layout.app')

@section('title', 'Barberos')

@section('content')
    <div class="space-y-6">

        {{-- ══════════════════════════════════════════════════════════
        HEADER + BOTÓN AGREGAR
        ══════════════════════════════════════════════════════════ --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">Gestión de Barberos</h1>
                <p class="text-sm text-gray-400 mt-0.5">Rendimiento y actividad del equipo</p>
            </div>
            <x-staff.add-staff-modal />
        </div>

        {{-- ══════════════════════════════════════════════════════════
        TARJETAS DE MÉTRICAS
        ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            {{-- Total Barberos --}}
            <div class="metric-card">
                <div class="metric-icon bg-violet-100 text-violet-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="5" />
                        <path d="M20 21a8 8 0 1 0-16 0" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="metric-label">Total Barberos</p>
                    <p class="metric-value">{{ $totalStaff }}</p>
                    <div class="metric-sub">
                        <span class="text-gray-400">Activos este mes:</span>
                        <span class="font-semibold text-gray-600 ml-1">{{ $activeThisMonth }}</span>
                    </div>
                </div>
            </div>

            {{-- Cortes este mes --}}
            <div class="metric-card">
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

            {{-- Promedio por barbero --}}
            <div class="metric-card">
                <div class="metric-icon bg-indigo-100 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                        <line x1="2" y1="20" x2="22" y2="20" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="metric-label">Promedio Cortes / Barbero</p>
                    <p class="metric-value">{{ $avgCutsPerStaff }}</p>
                    <div class="metric-sub">
                        <span class="text-gray-400">Mes anterior:</span>
                        <span class="font-semibold text-gray-600 ml-1">{{ $cutsLastMonth }}</span>
                    </div>
                </div>
            </div>

            {{-- Top Barbero --}}
            <div class="metric-card">
                <div class="metric-icon bg-rose-100 text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="metric-label">Top Barbero</p>
                    @if($topStaff)
                        <p class="text-lg font-extrabold text-gray-800 leading-tight mt-0.5">{{ $topStaff->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Más cortes este mes</p>
                    @else
                        <p class="text-lg font-extrabold text-gray-400 leading-tight mt-0.5">—</p>
                        <p class="text-xs text-gray-300 mt-0.5">Sin datos aún</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
        GRÁFICO + COMPARATIVA
        ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Gráfico de cortes por mes --}}
            <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Cortes por Mes</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Últimos 6 meses — todo el equipo</p>
                    </div>
                    <span class="flex items-center gap-1.5 text-xs text-gray-500">
                        <span class="w-3 h-3 rounded-full bg-violet-500 inline-block"></span> Cortes totales
                    </span>
                </div>
                <div class="relative h-52">
                    <canvas id="staffChart"></canvas>
                </div>
            </div>

            {{-- Comparativa mensual --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-5">
                <h2 class="text-base font-bold text-gray-800">Comparativa Mensual</h2>

                @php
                    $comparisons = [
                        ['label' => 'Cortes Realizados', 'current' => $cutsThisMonth, 'prev' => $cutsLastMonth, 'color' => 'amber'],
                        ['label' => 'Barberos Activos', 'current' => $activeThisMonth, 'prev' => $activeLastMonth, 'color' => 'violet'],
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
                            <div class="absolute top-0 left-0 h-full bg-{{ $comp['color'] }}-200 rounded-full"
                                style="width: {{ $prevPct }}%"></div>
                            <div class="absolute top-0 left-0 h-full bg-{{ $comp['color'] }}-500 rounded-full"
                                style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>Este mes: <strong class="text-gray-700">{{ $comp['current'] }}</strong></span>
                            <span>Anterior: <strong class="text-gray-700">{{ $comp['prev'] }}</strong></span>
                        </div>
                    </div>
                @endforeach

                {{-- Tasa de actividad --}}
                <div class="mt-auto pt-4 border-t border-gray-100">
                    @php $actPct = $totalStaff > 0 ? round(($activeThisMonth / $totalStaff) * 100) : 0; @endphp
                    <p class="text-xs text-gray-500 mb-1">Tasa de actividad del equipo</p>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-violet-500 to-indigo-500 rounded-full transition-all"
                                style="width: {{ $actPct }}%"></div>
                        </div>
                        <span class="text-sm font-bold text-violet-600">{{ $actPct }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
        TABLA DE BARBEROS
        ══════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-800">Equipo de Barberos</h2>
                <span class="text-xs text-gray-400">{{ $totalStaff }} {{ $totalStaff === 1 ? 'barbero' : 'barberos' }}
                    registrados</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3 text-left">Barbero</th>
                            <th class="px-6 py-3 text-left">Contacto</th>
                            <th class="px-6 py-3 text-left">Rol</th>
                            <th class="px-6 py-3 text-center">Cortes (Total)</th>
                            <th class="px-6 py-3 text-center">Este Mes</th>
                            <th class="px-6 py-3 text-center">Mes Anterior</th>
                            <th class="px-6 py-3 text-center">Tendencia</th>
                            <th class="px-6 py-3 text-left">Último Turno</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($staff as $member)
                            @php
                                $lastAppt = $member->appointments->first();
                                $trend = $member->cuts_this_month - $member->cuts_last_month;
                                $isTop = $topStaff && $topStaff->id === $member->id;
                            @endphp
                            <tr class="hover:bg-violet-50/30 transition-colors">

                                {{-- Avatar + Nombre --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($member->avatar)
                                            <img class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow"
                                                src="/media/{{ $member->avatar }}" alt="{{ $member->name }}">
                                        @else
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0 ring-2 ring-white shadow"
                                                style="background: {{ sprintf('hsl(%d, 65%%, 52%%)', crc32($member->name) % 360) }}">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <p class="font-semibold text-gray-800">{{ $member->name }}</p>
                                                @if($isTop)
                                                    <span class="text-amber-400" title="Top barbero este mes">★</span>
                                                @endif
                                            </div>
                                            @if($member->notes)
                                                <p class="text-xs text-gray-400 truncate max-w-[150px]">{{ $member->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Contacto --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-0.5">
                                        @if($member->email)
                                            <span class="flex items-center gap-1.5 text-xs text-gray-500">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                                </svg>
                                                {{ $member->email }}
                                            </span>
                                        @endif
                                        @if($member->phone)
                                            <span class="flex items-center gap-1.5 text-xs text-gray-500">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.8a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16z" />
                                                </svg>
                                                {{ $member->phone }}
                                            </span>
                                        @endif
                                        @if(!$member->email && !$member->phone)
                                            <span class="text-xs text-gray-300 italic">Sin contacto</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Rol --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    {{ $member->role === 'Admin' ? 'bg-violet-100 text-violet-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $member->role ?? 'Staff' }}
                                    </span>
                                </td>

                                {{-- Total cortes --}}
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-full text-sm font-bold
                                                    {{ $member->appointments_count > 10 ? 'bg-violet-100 text-violet-700' : ($member->appointments_count > 0 ? 'bg-gray-100 text-gray-600' : 'bg-gray-50 text-gray-400') }}">
                                        {{ $member->appointments_count }}
                                    </span>
                                </td>

                                {{-- Este mes --}}
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-sm font-bold {{ $member->cuts_this_month > 0 ? 'text-indigo-600' : 'text-gray-300' }}">
                                        {{ $member->cuts_this_month }}
                                    </span>
                                </td>

                                {{-- Mes anterior --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-semibold text-gray-400">
                                        {{ $member->cuts_last_month }}
                                    </span>
                                </td>

                                {{-- Tendencia --}}
                                <td class="px-6 py-4 text-center">
                                    @if($trend > 0)
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                                            ▲ +{{ $trend }}
                                        </span>
                                    @elseif($trend < 0)
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-full">
                                            ▼ {{ $trend }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-300 font-medium">— igual</span>
                                    @endif
                                </td>

                                {{-- Último turno --}}
                                <td class="px-6 py-4 text-xs text-gray-500">
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

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <x-staff.edit-staff-modal :staff="$member" />
                                        <x-staff.delete-staff-modal :staff="$member" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg class="w-12 h-12 text-gray-200" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <circle cx="12" cy="8" r="5" />
                                            <path d="M20 21a8 8 0 1 0-16 0" />
                                        </svg>
                                        <p class="text-sm font-medium">No hay barberos registrados</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const labels = @json(array_column($chartData, 'label'));
            const cuts = @json(array_column($chartData, 'cuts'));

            const ctx = document.getElementById('staffChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(139,92,246,0.3)');
            gradient.addColorStop(1, 'rgba(139,92,246,0)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Cortes',
                        data: cuts,
                        backgroundColor: cuts.map((_, i) =>
                            i === cuts.length - 1 ? 'rgba(139,92,246,0.9)' : 'rgba(139,92,246,0.35)'
                        ),
                        borderRadius: 8,
                        borderSkipped: false,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e1b4b',
                            titleColor: '#ddd6fe',
                            bodyColor: '#ede9fe',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y} cortes`,
                            },
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