<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel — Barber CRM</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
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

        <div class="flex-1 flex flex-col min-w-0">

            {{-- Top bar --}}
            <header
                class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span class="font-bold text-gray-800">💈 Barber CRM</span>
                    <span class="text-gray-300">/</span>
                    <span>Panel</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-xs text-gray-400 hidden sm:block" x-data="{ time: '' }" x-init="
                    const update = () => { time = new Date().toLocaleString('es-AR', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }); };
                    update(); setInterval(update, 1000);
                " x-text="time"></div>
                    <div
                        class="flex items-center gap-1.5 text-xs text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        En línea
                    </div>
                </div>
            </header>

            {{-- Main --}}
            <main class="flex-1 p-6 space-y-6 overflow-auto" x-data="{ 
                showQuickModal: false, 
                isGuest: false,
                isEditing: false,
                editId: null,
                form: {
                    client_id: '',
                    guest_name: '',
                    staff_id: '',
                    date: '{{ date('Y-m-d') }}',
                    start_time: '{{ date('H:i') }}',
                    end_time: '',
                    notes: '',
                    status: 'pendiente',
                    services: []
                },
                openCreate() {
                    this.isEditing = false;
                    this.editId = null;
                    this.isGuest = false;
                    this.form = {
                        client_id: '',
                        guest_name: '',
                        staff_id: '{{ $topTodayStaff->id ?? '' }}',
                        date: '{{ date('Y-m-d') }}',
                        start_time: '{{ date('H:i') }}',
                        end_time: '',
                        notes: '',
                        status: 'pendiente',
                        services: []
                    };
                    this.showQuickModal = true;
                },
                openEdit(appt) {
                    this.isEditing = true;
                    this.editId = appt.id;
                    this.isGuest = !appt.client_id;
                    this.form = {
                        client_id: appt.client_id || '',
                        guest_name: appt.guest_name || '',
                        staff_id: appt.staff_id,
                        date: appt.date.split(' ')[0],
                        start_time: appt.start_time.includes(' ') ? appt.start_time.split(' ')[1].substring(0, 5) : appt.start_time.substring(0, 5),
                        end_time: appt.end_time ? (appt.end_time.includes(' ') ? appt.end_time.split(' ')[1].substring(0, 5) : appt.end_time.substring(0, 5)) : '',
                        notes: appt.notes || '',
                        status: appt.status || 'pendiente',
                        services: appt.services ? appt.services.map(s => String(s.id)) : []
                    };
                    this.showQuickModal = true;
                }
            }">

                {{-- ══ SECCIÓN SUPERIOR: ACCIONES Y MÉTRICAS ════════════════════ --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Bienvenido,
                            {{ auth()->user()->name ?? 'Barbero' }}
                        </h1>
                        <p class="text-sm text-gray-400 font-medium">Aquí tienes el resumen de tu barbería para hoy.</p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="openCreate()"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Agendar Turno Rápido
                        </button>
                    </div>
                </div>

                {{-- ══ MÉTRICAS COMPACTAS ══════════════════════════════════════ --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Turnos Hoy --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Turnos Hoy</p>
                            <p class="text-lg font-black text-gray-800 leading-none">{{ $turnosHoy }}</p>
                        </div>
                    </div>

                    {{-- Semana --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18" /><path d="m19 9-5 5-4-4-3 3" /></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Semana</p>
                            <p class="text-lg font-black text-gray-800 leading-none">{{ $turnosSemana }}</p>
                        </div>
                    </div>

                    {{-- Clientes --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Clientes</p>
                            <p class="text-lg font-black text-gray-800 leading-none">{{ number_format($totalClients) }}</p>
                        </div>
                    </div>

                    {{-- Siguiente Turno (Destaque) --}}
                    <div class="bg-indigo-600 p-4 rounded-2xl shadow-lg border border-indigo-700 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="min-w-0 overflow-hidden">
                            <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mb-1">Próximo</p>
                            @if($nextAppointment)
                                <p class="text-xs font-bold text-white truncate leading-tight">
                                    {{ $nextAppointment->start_time ? \Carbon\Carbon::parse($nextAppointment->start_time)->format('H:i') : '' }} - {{ $nextAppointment->client_id ? $nextAppointment->client->name : $nextAppointment->guest_name }}
                                </p>
                            @else
                                <p class="text-xs font-bold text-white leading-tight">Sin turnos</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ══ SECCIÓN PRINCIPAL: AGENDA Y CALENDARIO ══════════════════ --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    {{-- AGENDA DE HOY (Columna izquierda) --}}
                    <div class="xl:col-span-1">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full sticky top-24">
                            <div
                                class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                <div>
                                    <h2 class="text-base font-bold text-gray-800">Agenda de Hoy</h2>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                        {{ now()->translatedFormat('l, d \d\e F') }}</p>
                                </div>
                                <span class="text-xs font-black px-3 py-1 rounded-full bg-indigo-100 text-indigo-700">
                                    {{ $turnosHoy }}
                                </span>
                            </div>
                            <div class="flex-1 overflow-y-auto divide-y divide-gray-50 max-h-[500px]">
                                @forelse($appointments as $appt)
                                    @php
                                        $startTime = $appt->start_time ? \Carbon\Carbon::parse($appt->start_time) : null;
                                        $endTime = $appt->end_time ? \Carbon\Carbon::parse($appt->end_time) : ($startTime ? $startTime->copy()->addHour() : null);
                                        $isPast = $startTime && $startTime->isPast() && $endTime && $endTime->isPast();
                                        $isNow = $startTime && $startTime->isPast() && $endTime && $endTime->isFuture();
                                        $clientName = $appt->client_id ? ($appt->client->name ?? 'Cliente') : ($appt->guest_name ?? 'Invitado');
                                        
                                        $statusColor = 'bg-gray-200';
                                        if($appt->status === 'completado') $statusColor = 'bg-emerald-500';
                                        elseif($appt->status === 'cancelado') $statusColor = 'bg-rose-500';
                                        elseif($appt->status === 'en_progreso' || $isNow) $statusColor = 'bg-amber-500';
                                        elseif(!$isPast) $statusColor = 'bg-indigo-500';
                                    @endphp
                                    <div class="group flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-all {{ $isPast && $appt->status !== 'en_progreso' ? 'opacity-60' : '' }}">
                                        <div class="text-center w-12 shrink-0">
                                            <p class="text-sm font-black text-gray-800">{{ $startTime ? $startTime->format('H:i') : '—' }}</p>
                                        </div>
                                        <div class="w-1.5 h-12 rounded-full {{ $statusColor }} shrink-0 transition-all group-hover:scale-y-110"></div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-bold text-gray-800 truncate">{{ $clientName }}</p>
                                                @if($isNow && $appt->status === 'pendiente')
                                                    <span class="flex h-2 w-2 rounded-full bg-amber-500 animate-ping"></span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-gray-400 font-semibold truncate leading-tight">
                                                <span class="text-indigo-500">{{ $appt->staff->name ?? '—' }}</span>
                                                @if($appt->notes) · {{ $appt->notes }} @endif
                                            </p>
                                            @if($appt->services->count() > 0)
                                                <div class="flex flex-wrap gap-1 mt-1.5 line-clamp-1">
                                                    @foreach($appt->services as $s)
                                                        <span class="text-[9px] font-black uppercase tracking-tighter bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded border border-gray-200">{{ $s->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            @if($isNow && $appt->status !== 'completado')
                                                <form action="{{ route('appointments.update', $appt->id) }}" method="POST" class="flex gap-1">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="completado">
                                                    <button type="submit" class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors" title="Completar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <button @click="openEdit({{ json_encode($appt) }})" class="p-1.5 bg-gray-50 text-gray-400 rounded-lg hover:bg-gray-100 hover:text-indigo-600 transition-colors" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-10 text-center flex flex-col items-center gap-3">
                                        <div
                                            class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                        </div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No hay turnos
                                            para hoy</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- CALENDARIO (Derecha) --}}
                    <div class="xl:col-span-2">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="text-base font-bold text-gray-800">Calendario de Turnos</h2>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="flex items-center gap-1.5 text-[10px] text-gray-400 font-black uppercase tracking-widest">
                                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Confirmado
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 flex-1">
                                <x-calendar :appointments="$calendarAppointments" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ SECCIÓN INFERIOR: GRÁFICOS Y ACTIVIDAD ══════════════════ --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Actividad Semanal --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Actividad Semanal</h2>
                            <span class="text-[10px] text-gray-400 font-bold">ÚLTIMOS 7 DÍAS</span>
                        </div>
                        <div class="h-56">
                            <canvas id="weekChart"></canvas>
                        </div>
                    </div>

                    {{-- Actividad Reciente --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Turnos Recientes</h2>
                            <a href="{{ route('appointments.index') }}"
                                class="text-[10px] font-black text-indigo-600 hover:text-indigo-700 transition-colors">VER
                                TODOS →</a>
                        </div>
                        <div class="divide-y divide-gray-50 flex-1 overflow-y-auto max-h-[300px]">
                            @forelse($recentAppointments as $appt)
                                <div class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs shrink-0 border border-indigo-100">
                                        {{ strtoupper(substr($appt->client->name ?? $appt->guest_name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 truncate">
                                            {{ $appt->client->name ?? $appt->guest_name ?? 'Desconocido' }}</p>
                                        <p class="text-[11px] text-gray-400 font-semibold">
                                            {{ \Carbon\Carbon::parse($appt->date)->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-black text-gray-700">
                                            {{ \Carbon\Carbon::parse($appt->date)->format('d/m') }}</p>
                                        <p class="text-[10px] text-indigo-500 font-black uppercase tracking-tighter">
                                            {{ $appt->staff->name ?? '' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-10 text-center text-xs text-gray-400 font-bold uppercase tracking-widest">Sin
                                    actividad</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ══ MODAL DE TURNO RÁPIDO ══════════════════════════════════ --}}
                <div x-show="showQuickModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;"
                    @keydown.escape.window="showQuickModal = false">
                    <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center sm:block sm:p-0">
                        <div x-show="showQuickModal" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-gray-900/80 backdrop-blur-md transition-opacity"
                            @click="showQuickModal = false"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>

                        <div x-show="showQuickModal" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="relative inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-white/20">

                            <form :action="isEditing ? '{{ url('appointments') }}/' + editId : '{{ route('appointments.store') }}'" method="POST">
                                @csrf
                                <template x-if="isEditing">
                                    @method('PUT')
                                </template>

                                <div class="bg-white p-6 sm:p-10">
                                    <div class="flex items-center justify-between mb-10">
                                        <div>
                                            <h3 class="text-3xl font-black text-gray-900 tracking-tight leading-none" x-text="isEditing ? 'Editar Turno' : 'Turno Rápido'"></h3>
                                            @if($allStaff->count() == 0)
                                                <p class="text-xs text-red-400 font-black uppercase tracking-widest mt-2">No hay barberos registrados, <a href="{{ route('staff') }}" class="text-xs text-red-500 font-black uppercase tracking-widest mt-2 underline">registre al menos un barbero</a> para continuar. </p>
                                            @endif
                                            <p class="text-xs text-gray-400 font-black uppercase tracking-widest mt-2" x-text="isEditing ? 'Modifica los detalles' : 'Agregado al instante'"></p>
                                        </div>
                                        <button type="button" @click="showQuickModal = false"
                                            class="p-3 rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="space-y-8">

                                        {{-- Selector Cliente vs Invitado --}}
                                        <div class="flex p-1.5 bg-gray-100 rounded-2xl">
                                            <button type="button" @click="isGuest = false"
                                                :class="!isGuest ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500'"
                                                class="flex-1 py-3 text-sm font-black rounded-xl transition-all">Cliente
                                                Registrado</button>
                                            <button type="button" @click="isGuest = true"
                                                :class="isGuest ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500'"
                                                class="flex-1 py-3 text-sm font-black rounded-xl transition-all">Invitado
                                                / Nuevo</button>
                                        </div>

                                        {{-- Campo de Cliente --}}
                                        <div x-show="!isGuest" x-transition>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Seleccionar
                                                Cliente</label>
                                            <select name="client_id" x-model="form.client_id"
                                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:bg-white text-gray-700 transition-all cursor-pointer">
                                                <option value="">Buscar cliente...</option>
                                                @foreach($allClients as $cli)
                                                    <option value="{{ $cli->id }}">{{ $cli->name }}
                                                        ({{ $cli->phone ?? 'Sin fono' }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div x-show="isGuest" x-transition>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Nombre
                                                del Invitado</label>
                                            <input type="text" name="guest_name" x-model="form.guest_name"
                                                placeholder="Ej: Juan Pérez (Caminante)"
                                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:bg-white text-gray-700 transition-all">
                                        </div>

                                        {{-- Barbero y Fecha --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Atendido
                                                    por</label>
                                                <select name="staff_id" required x-model="form.staff_id"
                                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:bg-white text-gray-700 transition-all cursor-pointer">
                                                    @foreach($allStaff as $st)
                                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Fecha</label>
                                                <input type="date" name="date" x-model="form.date" required
                                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:bg-white text-gray-700 transition-all">
                                            </div>
                                        </div>

                                        {{-- Hora y Estado --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Inicio</label>
                                                    <input type="time" name="start_time" x-model="form.start_time" required
                                                        class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:bg-white text-gray-700 transition-all text-center">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Fin</label>
                                                    <input type="time" name="end_time" x-model="form.end_time"
                                                        class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:bg-white text-gray-700 transition-all text-center"
                                                        placeholder="Opcional">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Estado</label>
                                                <select name="status" x-model="form.status" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:bg-white text-gray-700 transition-all cursor-pointer">
                                                    <option value="pendiente">Pendiente</option>
                                                    <option value="en_progreso">En Progreso</option>
                                                    <option value="completado">Completado</option>
                                                    <option value="cancelado">Cancelado</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Servicios --}}
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Servicios (Opcional)</label>
                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                @foreach($allServices as $svc)
                                                    <label class="relative flex flex-col p-3 border-2 rounded-xl cursor-pointer transition-all hover:bg-gray-50 select-none"
                                                        :class="form.services.includes('{{ $svc->id }}') ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-100 bg-white text-gray-500'">
                                                        <input type="checkbox" name="services[]" value="{{ $svc->id }}" 
                                                            class="hidden" x-model="form.services">
                                                        <span class="text-[10px] font-black truncate">{{ $svc->name }}</span>
                                                        <span class="text-[9px] opacity-70 mt-1">${{ number_format($svc->price, 0, ',', '.') }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @if($allServices->count() == 0)
                                                <p class="text-[10px] text-gray-400 italic">No hay servicios registrados.</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Notas
                                                Rápidas</label>
                                            <textarea name="notes" rows="2" x-model="form.notes"
                                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:bg-white text-gray-700 transition-all"
                                                placeholder="Ej: Degradado con barba..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 px-10 py-8 flex flex-col md:flex-row-reverse gap-4">
                                    <button type="submit"
                                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-[1.5rem] transition-all shadow-xl shadow-indigo-100 text-base"
                                        x-text="isEditing ? 'Actualizar Turno' : 'Confirmar Turno'"></button>
                                    <button type="button" @click="showQuickModal = false"
                                        class="flex-1 bg-white border-2 border-gray-100 text-gray-400 font-black py-5 rounded-[1.5rem] hover:bg-gray-100 transition-all">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

            <footer
                class="px-6 py-3 border-t border-gray-100 text-xs text-gray-400 flex items-center justify-between bg-white">
                <span>Barber CRM © {{ date('Y') }}</span>
                <span>v1.0.0</span>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const labels = @json(array_column($weekChart, 'label'));
            const counts = @json(array_column($weekChart, 'count'));
            const ctx = document.getElementById('weekChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 180);
            gradient.addColorStop(0, 'rgba(99,102,241,0.4)');
            gradient.addColorStop(1, 'rgba(99,102,241,0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        data: counts,
                        borderColor: '#6366f1',
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#6366f1',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e1b4b',
                            titleColor: '#c7d2fe',
                            bodyColor: '#e0e7ff',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: { label: ctx => ` ${ctx.parsed.y} turnos` },
                        },
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { color: '#9ca3af', font: { size: 11 }, precision: 0 } },
                    },
                },
            });
        })();
    </script>
</body>

</html>