<div class="flex flex-col h-full gap-3">
    {{-- ════ MÉTRICAS ════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 shrink-0">
        @php
            $views = ['hoy', 'semana', 'mes', 'historico'];
            $labels = ['hoy' => 'Hoy', 'semana' => 'Semana', 'mes' => 'Mes', 'historico' => 'Histórico'];
            $currentIndex = array_search($metricsView, $views);
            $nextView = $views[($currentIndex + 1) % count($views)];
        @endphp
        <div
            wire:click="setMetricsView('{{ $nextView }}')"
            class="bg-[#3B82F6] p-3 rounded-2xl shadow-md border border-[#2563EB] flex items-center gap-3 text-white cursor-pointer select-none hover:bg-[#2563EB] active:scale-[0.98] transition-all group"
            title="Click para cambiar período"
        >
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0 group-hover:bg-white/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg>
            </div>
            <div class="grid grid-cols-2 gap-x-2 gap-y-1 flex-1">
                <div class="col-span-2 flex items-center justify-between mb-0.5">
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Turnos · {{ $metricsLabel }}</p>
                    <span class="text-[8px] font-black uppercase opacity-60 bg-white/20 px-1.5 py-0.5 rounded-full tracking-widest">
                        → {{ $labels[$nextView] }}
                    </span>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5">Total</p>
                    <p class="text-xl font-black leading-none">{{ $metricsTotal }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5">Completados</p>
                    <p class="text-xl font-black leading-none">{{ $metricsCompleted }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5">Pendientes</p>
                    <p class="text-xl font-black leading-none @if($metricsPending) text-amber-300 @endif">{{ $metricsPending }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5">Cancelados</p>
                    <p class="text-xl font-black leading-none @if($metricsCanceled) text-red-300 @endif">{{ $metricsCanceled }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-3 rounded-2xl shadow-sm border border-[#E5E7EB] flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z" /><polyline points="12 6 12 12 16 14" /></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Turnos Hoy</p>
                <p class="text-xl font-black text-[#1F2937] leading-none">{{ $todayAppointments }}</p>
            </div>
        </div>
        <div class="bg-white p-3 rounded-2xl shadow-sm border border-[#E5E7EB] flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" /><line x1="16" y1="13" x2="8" y2="13" /><line x1="16" y1="17" x2="8" y2="17" /><polyline points="10 9 9 9 8 9" /></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Este Mes</p>
                <p class="text-xl font-black text-[#1F2937] leading-none">{{ $monthAppointments }}</p>
            </div>
        </div>
        <div class="bg-white p-3 rounded-2xl shadow-sm border border-[#E5E7EB] flex items-center gap-3 border-l-4 border-l-indigo-500">
            <div class="w-10 h-10 rounded-xl bg-[#3B82F6]/10 text-indigo-500 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" /></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Próximos 7 días</p>
                <p class="text-xl font-black text-[#3B82F6] leading-none">{{ $upcomingWeek }}</p>
            </div>
        </div>
    </div>

    {{-- ════ CALENDARIO + LISTA ════ --}}
    <div class="flex-1 grid grid-cols-1 xl:grid-cols-3 gap-3 min-h-0">
        {{-- Calendario (Col 2/3) --}}
        <div class="xl:col-span-2 flex flex-col min-h-0" x-data="{
            events: @js($events),
            calendar: null,
            initCalendar() {
                const el = this.$refs.calendarEl;
                if (!el || !window.FullCalendar) {
                    setTimeout(() => this.initCalendar(), 100);
                    return;
                }

                if (this.calendar) this.calendar.destroy();

                this.calendar = new window.FullCalendar.Calendar(el, {
                    plugins: [
                        window.FullCalendar.dayGridPlugin,
                        window.FullCalendar.timeGridPlugin,
                        window.FullCalendar.interactionPlugin
                    ],
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    locale: 'es',
                    firstDay: 1,
                    slotMinTime: '08:00:00',
                    slotMaxTime: '22:00:00',
                    allDaySlot: false,
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día',
                        list: 'Agenda',
                    },
                    height: '100%',
                    events: this.events,
                    editable: true,
                    selectable: true,
                    nowIndicator: true,
                    scrollTime: new Date().getHours() + ':00:00',
                    dateClick: (info) => {
                        const date = info.dateStr.split('T')[0];
                        const time = info.dateStr.split('T')[1] ? info.dateStr.split('T')[1].substring(0, 5) : '08:00';
                        this.$wire.openCreate(date, time);
                    },
                    eventClick: (info) => {
                        this.$wire.openEdit(info.event.id);
                    },
                    eventContent: function(arg) {
                        return {
                            html: `
                                <div class=&quot;p-1 overflow-hidden&quot;>
                                    <p class=&quot;text-[9px] font-black uppercase truncate leading-tight&quot;>${arg.event.title}</p>
                                    <p class=&quot;text-[8px] opacity-70 truncate&quot;>${arg.event.extendedProps.status}</p>
                                </div>
                            `
                        };
                    },
                    eventDrop: (info) => {
                        $wire.moveAppointment(
                            info.event.id,
                            info.event.startStr,
                            info.event.endStr
                        );
                    },
                });

                setTimeout(() => {
                    this.calendar.render();
                }, 50);
            },
            async refreshCalendar() {
                if (!this.calendar) return;
                const freshEvents = await this.$wire.getEvents();
                this.calendar.removeAllEvents();
                this.calendar.addEventSource(freshEvents);
            }
        }" x-init="initCalendar()"
           @calendar-updated.window="refreshCalendar()">
            <div class="bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] p-4 flex flex-col flex-1 min-h-0">
                <div class="flex items-center justify-between mb-3 shrink-0">
                    <div>
                        <h2 class="text-lg font-black text-[#1F2937] tracking-tight">Agenda</h2>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">Calendario interactivo de turnos</p>
                    </div>
                    <button wire:click="openCreate()" class="p-2.5 bg-[#3B82F6] text-white rounded-xl hover:bg-[#2563EB] transition-all shadow-md shadow-indigo-100 group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" class="group-hover:scale-110 transition-transform"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                    </button>
                </div>
                <div class="flex-1 min-h-0" x-ref="calendarEl" wire:ignore></div>
            </div>
        </div>

        {{-- Lista + Filtros (Col 1/3) --}}
        <div class="flex flex-col gap-3 min-h-0">
            {{-- Filtros --}}
            <div class="bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] overflow-hidden shrink-0">
                <div class="px-4 py-3 border-b border-[#E5E7EB] bg-white">
                    <h3 class="text-xs font-black text-[#1F2937] uppercase tracking-widest">Filtros de Lista</h3>
                </div>
                <div class="p-3 space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Desde</label>
                            <input type="date" wire:model.live="date_from" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-1.5 px-3 text-xs font-bold text-gray-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Hasta</label>
                            <input type="date" wire:model.live="date_to" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-1.5 px-3 text-xs font-bold text-gray-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Barbero</label>
                        <select wire:model.live="staff_id" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-1.5 px-3 text-xs font-bold text-gray-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                            <option value="">Todos los barberos</option>
                            @foreach($staffMembers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="$set('status_filter', 'upcoming')" class="flex-1 py-1.5 px-3 rounded-lg text-[10px] font-black uppercase tracking-tighter transition-all {{ $status_filter === 'upcoming' ? 'bg-[#3B82F6] text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">Futuros</button>
                        <button wire:click="$set('status_filter', 'past')" class="flex-1 py-1.5 px-3 rounded-lg text-[10px] font-black uppercase tracking-tighter transition-all {{ $status_filter === 'past' ? 'bg-[#3B82F6] text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">Pasados</button>
                        <button wire:click="$set('status_filter', '')" class="py-1.5 px-3 rounded-lg text-[10px] font-black uppercase tracking-tighter bg-white text-gray-500">Todo</button>
                    </div>
                </div>
            </div>

            {{-- Lista de Turnos --}}
            <div class="bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] overflow-hidden flex flex-col flex-1 min-h-0">
                <div class="px-4 py-3 border-b border-[#E5E7EB] flex items-center justify-between shrink-0">
                    <h3 class="text-xs font-black text-[#1F2937] uppercase tracking-widest">Lista de turnos</h3>
                    <span class="text-[10px] font-black bg-[#3B82F6]/10 text-[#3B82F6] px-2 py-0.5 rounded-full">{{ $appointments->total() }}</span>
                </div>
                <div class="divide-y divide-[#E5E7EB] flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($appointments as $appt)
                        <div class="p-3 hover:bg-slate-50/50 transition-all group">
                            <div class="flex items-start justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-[#3B82F6]/10 text-[#3B82F6] flex items-center justify-center font-black text-[10px] shrink-0">
                                        {{ \Carbon\Carbon::parse($appt->date)->format('d') }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-[#1F2937] group-hover:text-indigo-600 transition-colors">{{ $appt->client->name ?? $appt->guest_name }}</p>
                                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">{{ \Carbon\Carbon::parse($appt->start_time)->format('H:i') }} — {{ $appt->staff->name ?? 'Barbero' }}</p>
                                    </div>
                                </div>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1 text-gray-500 hover:text-indigo-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="1" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="5" r="1" /></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-xl border border-[#E5E7EB] z-10 p-1 flex flex-col gap-1">
                                        <button wire:click="openEdit({{ $appt->id }})" class="w-full text-left px-3 py-1.5 text-[10px] font-bold text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">Editar</button>
                                        <button wire:click="confirmDelete({{ $appt->id }})" class="w-full text-left px-3 py-1.5 text-[10px] font-bold text-rose-400 hover:bg-rose-50 rounded-lg">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $badgeClass = match($appt->status) {
                                        'completado' => 'bg-emerald-500/10 text-emerald-400',
                                        'en_progreso' => 'bg-[#3B82F6]/10 text-[#3B82F6]',
                                        'cancelado' => 'bg-rose-500/10 text-rose-400',
                                        default => 'bg-amber-500/10 text-amber-400',
                                    };
                                    $status = match($appt->status) {
                                        'en_progreso' => 'en progreso',
                                        default => $appt->status
                                    }
                                @endphp
                                <span class="text-[9px] font-black uppercase tracking-tighter px-2 py-0.5 rounded-full {{ $badgeClass }}">{{ $status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">No hay turnos registrados</p>
                        </div>
                    @endforelse
                </div>
                @if($appointments->hasPages())
                    <div class="p-3 bg-white border-t border-[#E5E7EB] shrink-0">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ════ MODAL TURNO ════ --}}
    <div x-data="{ show: $wire.entangle('showModal') }" x-show="show" style="display:none" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div x-show="show" x-transition.opacity class="absolute inset-0 bg-[#1E293B]/60 backdrop-blur-sm" @click="show = false"></div>
        <div x-show="show" x-transition.scale class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl z-10 max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-[#1F2937] tracking-tight">{{ $isEditing ? 'Editar Turno' : 'Nuevo Turno' }}</h3>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Configuración de la cita</p>
                    </div>
                    <button @click="show = false" class="text-gray-500 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-5">
                    <div class="grid grid-cols-1 gap-5">
                        {{-- Cliente --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Cliente</label>
                            <div class="flex gap-2">
                                <div class="flex-1">
                                    <select wire:model="client_id" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                        <option value="">Invitado / Anonimo</option>
                                        @foreach($allClients as $cli)
                                            <option value="{{ $cli->id }}">{{ $cli->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(!$client_id)
                                <div class="flex-1">
                                    <input type="text" wire:model="guest_name" placeholder="Nombre completo" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Barbero --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Barbero</label>
                                <select wire:model="selected_staff_id" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                    <option value="">Selecciona...</option>
                                    @foreach($staffMembers as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                @error('selected_staff_id') <span class="text-[10px] text-rose-500 font-bold mt-1 block">Obligatorio</span> @enderror
                            </div>
                            {{-- Estado --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Estado</label>
                                <select wire:model="status" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_progreso">En progreso</option>
                                    <option value="completado">Completado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            {{-- Fecha --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Fecha</label>
                                <input type="date" wire:model="date" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-xs font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                            </div>
                            {{-- Inicio --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Inicio</label>
                                <input type="time" wire:model="start_time" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-xs font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                            </div>
                            {{-- Fin --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Fin (opcional)</label>
                                <input type="time" wire:model="end_time" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-xs font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                            </div>
                        </div>

                        {{-- Servicios --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Servicios</label>
                            <div class="grid grid-cols-2 gap-2 max-h-32 overflow-y-auto p-2 bg-white rounded-xl border border-[#E5E7EB]">
                                @foreach($allServices as $svc)
                                    <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg transition-all cursor-pointer">
                                        <input type="checkbox" wire:model="selected_services" value="{{ $svc->id }}" class="rounded text-[#3B82F6] focus:ring-indigo-400">
                                        <span class="text-[11px] font-bold text-gray-500">{{ $svc->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Notas Internas</label>
                            <textarea wire:model="notes" rows="2" class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all resize-none"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="show = false" class="flex-1 bg-white border-2 border-[#E5E7EB] text-gray-500 font-black py-4 rounded-2xl hover:bg-gray-50 transition-all text-xs uppercase tracking-widest">Cancelar</button>
                        <button type="submit" class="flex-1 bg-[#3B82F6] hover:bg-[#2563EB] text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 text-xs uppercase tracking-widest">
                            <span wire:loading.remove>Guardar Turno</span>
                            <span wire:loading>Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════ MODAL ELIMINAR ════ --}}
    <div x-data="{ toDelete: $wire.entangle('appointmentToDelete') }" x-show="toDelete" style="display:none" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div x-show="toDelete" x-transition.opacity class="absolute inset-0 bg-[#1E293B]/60 backdrop-blur-sm" @click="toDelete = null"></div>
        <div x-show="toDelete" x-transition.scale class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl z-10 overflow-hidden text-center p-8">
            <div class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" /></svg>
            </div>
            <h3 class="text-xl font-black text-[#1F2937] mb-2">¿Eliminar turno?</h3>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest leading-relaxed">Se perderá toda la información de esta cita.</p>
            <div class="flex gap-3 mt-8">
                <button @click="toDelete = null" class="flex-1 bg-white text-gray-500 font-black py-3.5 rounded-xl hover:bg-gray-50 transition-all text-[10px] uppercase tracking-widest">Cancelar</button>
                <button wire:click="delete" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-black py-3.5 rounded-xl transition-all shadow-lg shadow-rose-200 text-[10px] uppercase tracking-widest">Eliminar</button>
            </div>
        </div>
    </div>
</div>
