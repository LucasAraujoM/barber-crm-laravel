<div>

    {{-- ══ ACCIONES Y TÍTULO ══════════════════════════════ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1F2937] tracking-tight">
                Bienvenido, {{ auth()->user()->name ?? 'Barbero' }}
            </h1>
            <p class="text-sm text-gray-500 font-medium">Aquí tienes el resumen de tu barbería para hoy.</p>
        </div>
        <div class="flex gap-2">
            <button type="button" wire:click="resetModal"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#3B82F6] hover:bg-[#2563EB] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Agendar Turno Rápido
            </button>
        </div>
    </div>

    {{-- ══ MÉTRICAS COMPACTAS ══════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-1 mt-4">

        {{-- Turnos Hoy --}}
        <div
            class="bg-white p-3 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3 text-[#1F2937]">
            <div
                class="w-10 h-10 rounded-xl bg-[#3B82F6]/10 text-[#3B82F6] flex items-center justify-center shrink-0 border border-[#3B82F6]/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Turnos Hoy</p>
                <p class="text-lg font-black text-[#1F2937] leading-none">{{ $turnosHoy }}</p>
            </div>
        </div>

        {{-- Semana --}}
        <div
            class="bg-white p-3 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3 text-[#1F2937]">
            <div
                class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center shrink-0 border border-sky-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 3v18h18" />
                    <path d="m19 9-5 5-4-4-3 3" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Semana</p>
                <p class="text-lg font-black text-[#1F2937] leading-none">{{ $turnosSemana }}</p>
            </div>
        </div>

        {{-- Clientes --}}
        <div
            class="bg-white p-3 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3 text-[#1F2937]">
            <div
                class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Clientes</p>
                <p class="text-lg font-black text-[#1F2937] leading-none">{{ number_format($totalClients) }}</p>
            </div>
        </div>

        {{-- Próximo Turno --}}
        <div class="bg-[#3B82F6] p-3 rounded-2xl shadow-md border border-[#2563EB] flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 text-white flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="min-w-0 overflow-hidden">
                <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mb-1">Próximo</p>
                @if($nextAppointment)
                    <p class="text-xs font-bold text-white truncate leading-tight">
                        {{ $nextAppointment->start_time ? \Carbon\Carbon::parse($nextAppointment->start_time)->format('H:i') : '' }}
                        —
                        {{ $nextAppointment->client_id ? $nextAppointment->client->name : $nextAppointment->guest_name }}
                    </p>
                @else
                    <p class="text-xs font-bold text-white leading-tight">Sin turnos</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ AGENDA + CALENDARIO ════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-1 mt-2">

        {{-- Columna Izquierda --}}
        <div class="xl:col-span-1 flex flex-col gap-1">
            {{-- Agenda de Hoy --}}
            <div
                class="bg-white rounded-[1.25rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.3)] border border-[#E5E7EB] overflow-hidden flex flex-col">
                <div class="px-4 py-3 border-b border-[#E5E7EB] flex items-center justify-between bg-white/80">
                    <div>
                        <h2 class="text-sm font-bold text-[#1F2937]">Agenda de Hoy</h2>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                            {{ now()->translatedFormat('l, d \d\e F') }}
                        </p>
                    </div>
                    <span
                        class="text-xs font-black px-3 py-1 rounded-full bg-[#3B82F6]/10 border border-[#3B82F6]/20 text-[#3B82F6]">
                        {{ $turnosHoy }}
                    </span>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-[#E5E7EB] max-h-[460px] custom-scrollbar">
                    @forelse($appointments as $appt)
                        @php
                            $startTime = $appt->start_time ? \Carbon\Carbon::parse($appt->start_time) : null;
                            $endTime = $appt->end_time ? \Carbon\Carbon::parse($appt->end_time) : ($startTime ? $startTime->copy()->addHour() : null);
                            $isPast = $startTime && $startTime->isPast() && $endTime && $endTime->isPast();
                            $isNow = $startTime && $startTime->isPast() && $endTime && $endTime->isFuture();
                            $clientName = $appt->client_id ? ($appt->client->name ?? 'Cliente') : ($appt->guest_name ?? 'Invitado');
                            $statusColor = 'bg-white';
                            if ($appt->status === 'completado')
                                $statusColor = 'bg-emerald-500';
                            elseif ($appt->status === 'cancelado')
                                $statusColor = 'bg-rose-500';
                            elseif ($appt->status === 'en_progreso' || $isNow)
                                $statusColor = 'bg-amber-500';
                            elseif (!$isPast)
                                $statusColor = 'bg-indigo-500';
                        @endphp
                        <div
                            class="group flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-all {{ $isPast && $appt->status !== 'en_progreso' ? 'opacity-60' : '' }}">
                            <div class="text-center w-10 shrink-0">
                                <p class="text-xs font-black text-[#1F2937]">
                                    {{ $startTime ? $startTime->format('H:i') : '—' }}
                                </p>
                            </div>
                            <div
                                class="w-1.5 h-12 rounded-full {{ $statusColor }} shrink-0 transition-all group-hover:scale-y-110">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-[#1F2937] truncate">{{ $clientName }}</p>
                                    @if($isNow && $appt->status === 'pendiente')
                                        <span class="flex h-2 w-2 rounded-full bg-amber-500 animate-ping"></span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-500 font-semibold truncate leading-tight">
                                    <span class="text-[#3B82F6]">{{ $appt->staff->name ?? '—' }}</span>
                                    @if($appt->notes) · <span class="text-gray-500">{{ $appt->notes }}</span> @endif
                                </p>
                                @if($appt->services->count() > 0)
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @foreach($appt->services as $s)
                                            <span
                                                class="text-[9px] font-black uppercase tracking-tighter bg-[#F5F7FA] text-gray-500 px-1.5 py-0.5 rounded border border-[#E5E7EB]">{{ $s->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($isNow && $appt->status !== 'completado')
                                    <button wire:click="completeAppointment({{ $appt->id }})"
                                        class="p-1.5 bg-emerald-500/10 text-emerald-400 rounded-lg hover:bg-emerald-100 transition-colors"
                                        title="Completar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </button>
                                @endif
                                <button type="button" wire:click="editAppointment({{ $appt->id }})"
                                    class="p-1.5 bg-[#F5F7FA] border border-[#E5E7EB] text-gray-500 rounded-lg hover:bg-gray-50 hover:text-indigo-400 transition-colors"
                                    title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center flex flex-col items-center gap-3">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">No hay turnos para hoy
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Turnos Recientes --}}
            <div
                class="bg-white rounded-[1.25rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.3)] border border-[#E5E7EB] overflow-hidden flex flex-col">
                <div class="px-4 py-3 border-b border-[#E5E7EB] flex items-center justify-between bg-white/80">
                    <h2 class="text-sm font-bold text-[#1F2937]">Turnos Recientes</h2>
                    <a href="{{ route('appointments.index') }}" wire:navigate
                        class="text-[10px] font-black text-[#3B82F6] hover:text-indigo-300 transition-colors">VER TODOS
                        →</a>
                </div>
                <div class="divide-y divide-[#E5E7EB] flex-1 overflow-y-auto max-h-[250px] custom-scrollbar">
                    @forelse($recentAppointments as $appt)
                        <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div
                                class="w-8 h-8 rounded-full bg-[#3B82F6]/10 text-[#3B82F6] flex items-center justify-center font-black text-[10px] shrink-0 border border-[#3B82F6]/20">
                                {{ strtoupper(substr($appt->client->name ?? $appt->guest_name ?? '?', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-[#1F2937] truncate">
                                    {{ $appt->client->name ?? $appt->guest_name ?? 'Desconocido' }}
                                </p>
                                <p class="text-[11px] text-gray-500 font-semibold">
                                    {{ \Carbon\Carbon::parse($appt->date)->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs font-black text-gray-500">
                                    {{ \Carbon\Carbon::parse($appt->date)->format('d/m') }}
                                </p>
                                <p class="text-[10px] text-[#3B82F6] font-black uppercase tracking-tighter">
                                    {{ $appt->staff->name ?? '' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-xs text-gray-500 font-bold uppercase tracking-widest">Sin
                            actividad</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Calendario --}}
        <div class="xl:col-span-3">
            <div
                class="bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] overflow-hidden flex flex-col h-full">
                <div class="px-3 py-2 border-b border-[#E5E7EB] flex items-center justify-between">
                    <h2 class="text-sm font-bold text-[#1F2937]">Calendario de Turnos</h2>
                    <span
                        class="flex items-center gap-1.5 text-[10px] text-gray-500 font-black uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Confirmado
                    </span>
                </div>
                <div class="p-2 flex-1">
                    <x-calendar :appointments="$calendarAppointments" />
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL DE TURNO RÁPIDO --}}
    <div x-data="{ show: @entangle('showQuickModal') }" x-show="show" style="display:none"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="show = false">

        {{-- Backdrop --}}
        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-[#1E293B]/60 backdrop-blur-sm" @click="show = false">
        </div>

        {{-- Panel del modal --}}
        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto bg-white border border-[#E5E7EB] rounded-[2rem] shadow-2xl z-10">

            <form wire:submit.prevent="saveAppointment">

                <div class="p-8">
                    {{-- Header del modal --}}
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-[#1F2937] tracking-tight leading-none">
                                {{ $editing_id ? 'Editar Turno' : 'Turno Rápido' }}
                            </h3> @if($allStaff->count() == 0)
                                <p class="text-xs text-rose-400 font-black uppercase tracking-widest mt-2">
                                    No hay barberos registrados.
                                    <a href="{{ route('staff') }}" wire:navigate class="underline text-rose-500">Registrar
                                        uno</a>
                                </p>
                            @endif
                            <p class="text-xs text-gray-500 font-black uppercase tracking-widest mt-1">
                                {{ $editing_id ? 'Actualizando datos' : 'Agregado al instante' }}
                            </p>
                        </div>
                        <button type="button" @click="show = false"
                            class="p-2 rounded-full hover:bg-gray-50 text-gray-500 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">

                        {{-- 1. FECHA Y HORA --}}
                        <div class="bg-indigo-500/5 p-5 rounded-2xl border border-[#3B82F6]/20">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-[#3B82F6] uppercase tracking-widest mb-2">Fecha</label>
                                    <input type="date" wire:model="date" required
                                        class="w-full bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl py-3 px-3 text-sm font-bold text-[#1F2937] text-center focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-[#3B82F6] uppercase tracking-widest mb-2">Hora
                                        Inicio</label>
                                    <input type="time" wire:model="start_time" required
                                        class="w-full bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl py-3 px-3 text-sm font-bold text-[#1F2937] text-center focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-[#3B82F6] uppercase tracking-widest mb-2">Hora
                                        Fin</label>
                                    <input type="time" wire:model="end_time"
                                        class="w-full bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl py-3 px-3 text-sm font-bold text-[#1F2937] text-center focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- 2. BARBERO --}}
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label
                                    class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Seleccionar
                                    Barbero</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($allStaff as $st)
                                        <button type="button" wire:click="$set('selected_staff_id', {{ $st->id }})"
                                            class="relative flex flex-col items-center p-4 border-2 rounded-2xl transition-all group overflow-hidden {{ $selected_staff_id == $st->id ? 'border-indigo-500 bg-[#3B82F6]/10 ring-2 ring-indigo-500/20' : 'border-[#E5E7EB] hover:border-indigo-400/50 hover:bg-gray-50' }}">
                                            <div
                                                class="w-10 h-10 rounded-full bg-[#F5F7FA] border border-[#E5E7EB] flex items-center justify-center mb-2 shadow-sm">
                                                <span
                                                    class="text-[10px] font-black text-[#3B82F6]">{{ strtoupper(substr($st->name, 0, 2)) }}</span>
                                            </div>
                                            <span class="text-[11px] font-black text-[#1F2937]">{{ $st->name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- 3. CLIENTE O INVITADO --}}
                            <div x-data="{ isGuest: false }" @init-edit-modal.window="isGuest = $event.detail.isGuest">
                                <div class="flex p-1 bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl mb-4">
                                    <button type="button" @click="isGuest = false"
                                        :class="!isGuest ? 'bg-white text-[#3B82F6] shadow-sm border border-[#E5E7EB]' : 'text-gray-500'"
                                        class="flex-1 py-2.5 text-sm font-black rounded-lg transition-all border border-transparent">Cliente
                                        Registrado</button>
                                    <button type="button" @click="isGuest = true"
                                        :class="isGuest ? 'bg-white text-[#3B82F6] shadow-sm border border-[#E5E7EB]' : 'text-gray-500'"
                                        class="flex-1 py-2.5 text-sm font-black rounded-lg transition-all border border-transparent">Invitado
                                        /
                                        Nuevo</button>
                                </div>

                                <div x-show="!isGuest" x-transition>
                                    <label
                                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Seleccionar
                                        Cliente</label>
                                    <select wire:model="client_id"
                                        class="w-full bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                        <option value="">Buscar cliente...</option>
                                        @foreach($allClients as $cli)
                                            <option value="{{ $cli->id }}">{{ $cli->name }}
                                                ({{ $cli->phone ?? 'Sin teléfono' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="isGuest" x-transition>
                                    <label
                                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Nombre
                                        del Invitado</label>
                                    <input type="text" wire:model="guest_name" placeholder="Ej: Juan Pérez"
                                        class="w-full bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            {{-- 4. SERVICIOS Y ESTADO --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Servicios
                                        (Opcional)</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($allServices as $svc)
                                            <label
                                                class="relative flex flex-col p-3 border-2 border-[#E5E7EB] rounded-xl cursor-pointer transition-all hover:bg-gray-50 select-none text-center">
                                                <input type="checkbox" wire:model="selected_services" value="{{ $svc->id }}"
                                                    class="hidden">
                                                <span
                                                    class="text-[9px] text-[#1F2937] font-black uppercase tracking-tighter truncate">{{ $svc->name }}</span>
                                                <span
                                                    class="text-[8px] text-gray-500 opacity-60 mt-0.5">${{ number_format($svc->price, 0, ',', '.') }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Estado
                                        y Notas</label>
                                    <div class="space-y-3">
                                        <select wire:model="status"
                                            class="w-full bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="pendiente">Pendiente</option>
                                            <option value="en_progreso">En Progreso</option>
                                            <option value="completado">Completado</option>
                                            <option value="cancelado">Cancelado</option>
                                        </select>

                                        <textarea wire:model="notes" rows="2" placeholder="Notas adicionales..."
                                            class="w-full bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm text-[#1F2937] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 custom-scrollbar resize-none"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Footer del modal --}}
                    <div
                        class="bg-[#F5F7FA] border-t border-[#E5E7EB] px-8 py-6 flex flex-col sm:flex-row-reverse gap-3 rounded-b-[2rem]">
                        <button type="submit"
                            class="flex-1 bg-[#3B82F6] hover:bg-[#2563EB] text-white font-black py-4 rounded-2xl transition-all shadow-md shadow-blue-500/20 text-sm">
                            {{ $editing_id ? 'Actualizar Turno' : 'Confirmar Turno' }}
                        </button>

                        @if($editing_id)
                            <button type="button" wire:click="deleteAppointment({{ $editing_id }})"
                                class="flex-1 bg-rose-50 border-2 border-rose-100 text-rose-500 font-black py-4 rounded-2xl hover:bg-rose-500 hover:text-white transition-all text-sm">
                                Eliminar Turno
                            </button>
                        @endif

                        <button type="button" @click="show = false"
                            class="flex-1 bg-white border-2 border-[#E5E7EB] text-gray-500 font-black py-4 rounded-2xl hover:bg-gray-50 hover:text-[#1F2937] transition-all text-sm">
                            Cancelar
                        </button>
                    </div>
            </form>

        </div>
    </div>
</div>