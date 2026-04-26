<div class="flex flex-col h-full gap-4 min-h-0">

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 shrink-0">
        <div class="card bg-primary text-primary-content border border-primary">
            <div class="card-body p-4 flex-row items-center gap-3 text-white">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                <div class="grid grid-cols-2 gap-x-2 gap-y-1 flex-1">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider opacity-80 mb-0.5">Turnos Hoy</p>
                        <p class="text-xl font-black leading-none">{{ $totalAppointments }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider opacity-80 mb-0.5">Completados</p>
                        <p class="text-xl font-black leading-none">{{ $todayAppointmentsCompleted }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider opacity-80 mb-0.5">Pendientes</p>
                        <p
                            class="text-xl font-black leading-none @if($todayAppointmentsPending) text-warning-content @endif">
                            {{ $todayAppointmentsPending }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider opacity-80 mb-0.5">Cancelados</p>
                        <p
                            class="text-xl font-black leading-none @if($todayAppointmentsCanceled) text-error-content @endif">
                            {{ $todayAppointmentsCanceled }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="badge badge-warning badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                        height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg></div>
                <div>
                    <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Turnos Hoy</p>
                    <p class="text-xl font-black">{{ $todayAppointments }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="badge badge-success badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                        height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg></div>
                <div>
                    <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Este Mes</p>
                    <p class="text-xl font-black">{{ $monthAppointments }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 border-l-4 border-l-primary">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="badge badge-primary badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                        height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path
                            d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                    </svg></div>
                <div>
                    <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Próximos 7 días</p>
                    <p class="text-xl font-black text-primary">{{ $upcomingWeek }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 grid grid-cols-1 xl:grid-cols-3 gap-4 min-h-0">

        <div class="xl:col-span-2 flex flex-col min-h-0" wire:ignore x-data="{
            events: @js($events),
            calendar: null,
            initCalendar() {
                const el = this.$refs.calendarEl;
                if (!el || !window.FullCalendar) { setTimeout(() => this.initCalendar(), 100); return; }
                if (this.calendar) this.calendar.destroy();
                this.calendar = new window.FullCalendar.Calendar(el, {
                    plugins: [window.FullCalendar.dayGridPlugin, window.FullCalendar.timeGridPlugin, window.FullCalendar.interactionPlugin],
                    initialView: 'timeGridWeek',
                    headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                    locale: 'es', firstDay: 1, slotMinTime: '08:00:00', slotMaxTime: '22:00:00', allDaySlot: false,
                    buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día', list: 'Agenda' },
                    height: '100%', events: this.events, editable: true, selectable: true, nowIndicator: true,
                    scrollTime: new Date().getHours() + ':00:00',
                    dateClick: (info) => { const date = info.dateStr.split('T')[0]; const time = info.dateStr.split('T')[1] ? info.dateStr.split('T')[1].substring(0, 5) : '08:00'; this.$wire.openCreate(date, time); },
                    eventClick: (info) => { this.$wire.openEdit(info.event.id); },
                    eventContent: function(arg) { return { html: '<div class=&quot;p-1 overflow-hidden&quot;><p class=&quot;text-[9px] font-black uppercase truncate leading-tight&quot;>' + arg.event.title + '</p></div>' }; },
                    eventDrop: (info) => { $wire.moveAppointment(info.event.id, info.event.startStr, info.event.endStr); },
                });
                setTimeout(() => { this.calendar.render(); }, 50);
            },
            async refreshCalendar() {
                if (!this.calendar) return;
                const freshEvents = await this.$wire.getEvents();
                this.calendar.removeAllEvents();
                this.calendar.addEventSource(freshEvents);
            }
        }" x-init="initCalendar()" @calendar-updated.window="refreshCalendar()">
            <div class="card bg-base-100 border border-base-300 flex flex-col flex-1 min-h-0">
                <div class="card-body flex-none p-4 pb-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-black">Agenda</h2>
                            <p class="text-[10px] text-base-content/50 font-bold uppercase tracking-wider mt-0.5">
                                Calendario interactivo de turnos</p>
                        </div>
                        <button wire:click="openCreate()" class="btn btn-primary btn-sm btn-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-2 flex-1 min-h-0" x-ref="calendarEl" wire:ignore></div>
            </div>
        </div>

        <div class="flex flex-col gap-3 min-h-0">

            <div class="card bg-base-100 border border-base-300 overflow-hidden shrink-0">
                <div class="card-body p-4 pb-2">
                    <h3 class="text-xs font-black uppercase tracking-wider">Filtros de Lista</h3>
                </div>
                <div class="p-3 space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="form-control">
                            <label class="label py-0"><span
                                    class="label-text text-[10px] font-bold uppercase tracking-wider text-base-content/50">Desde</span></label>
                            <input type="date" wire:model.live="date_from" class="input input-bordered input-sm">
                        </div>
                        <div class="form-control">
                            <label class="label py-0"><span
                                    class="label-text text-[10px] font-bold uppercase tracking-wider text-base-content/50">Hasta</span></label>
                            <input type="date" wire:model.live="date_to" class="input input-bordered input-sm">
                        </div>
                    </div>
                    <div class="form-control">
                        <label class="label py-0"><span
                                class="label-text text-[10px] font-bold uppercase tracking-wider text-base-content/50">Barbero</span></label>
                        <select wire:model.live="staff_id" class="select select-bordered select-sm">
                            <option value="">Todos los barberos</option>
                            @foreach($staffMembers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="join w-full">
                        <button wire:click="$set('status_filter', 'upcoming')"
                            class="join-item flex-1 btn btn-sm {{ $status_filter === 'upcoming' ? 'btn-primary' : 'btn-ghost' }}">Futuros</button>
                        <button wire:click="$set('status_filter', 'past')"
                            class="join-item flex-1 btn btn-sm {{ $status_filter === 'past' ? 'btn-primary' : 'btn-ghost' }}">Pasados</button>
                        <button wire:click="$set('status_filter', '')"
                            class="join-item btn btn-sm btn-ghost">Todo</button>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300 overflow-hidden flex flex-col flex-1 min-h-0">
                <div class="card-body flex-none p-4 pb-0">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-black uppercase tracking-wider">Turnos Recientes</h3>
                        <span class="badge badge-primary badge-sm">{{ $appointments->total() }}</span>
                    </div>
                </div>
                <div class="divide-y divide-base-300 flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($appointments as $appt)
                        <div class="p-3 hover:bg-base-200/50 transition-all group">
                            <div class="flex items-start justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-black text-[10px] shrink-0">
                                        {{ \Carbon\Carbon::parse($appt->date)->format('d') }}</div>
                                    <div>
                                        <p class="text-xs font-black group-hover:text-primary transition-colors">
                                            {{ $appt->client->name ?? $appt->guest_name }}</p>
                                        <p class="text-[10px] text-base-content/50 font-bold uppercase tracking-tighter">
                                            {{ \Carbon\Carbon::parse($appt->start_time)->format('H:i') }} —
                                            {{ $appt->staff->name ?? 'Barbero' }}</p>
                                    </div>
                                </div>
                                <div class="dropdown dropdown-end" x-data="{ open: false }">
                                    <button @click="open = !open" class="btn btn-xs btn-ghost btn-circle"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="12" cy="19" r="1" />
                                            <circle cx="12" cy="5" r="1" />
                                        </svg></button>
                                    <ul x-show="open" @click.away="open = false" x-transition.opacity
                                        class="dropdown-content z-10 menu p-1 shadow bg-base-100 rounded-box w-32">
                                        <li><button wire:click="openEdit({{ $appt->id }})"
                                                class="text-xs font-bold">Editar</button></li>
                                        <li><button wire:click="confirmDelete({{ $appt->id }})"
                                                class="text-xs font-bold text-error">Eliminar</button></li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $badgeClass = match ($appt->status) {
                                    'completado' => 'badge-success', 'en_progreso' => 'badge-primary',
                                    'cancelado' => 'badge-error', default => 'badge-warning',
                                };
                            @endphp
                            <span class="badge badge-sm {{ $badgeClass }}">{{ $appt->status }}</span>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-[10px] font-black text-base-content/50 uppercase tracking-wider">No hay turnos
                                registrados</p>
                        </div>
                    @endforelse
                </div>
                @if($appointments->hasPages())
                    <div class="card-body p-3 pt-0 shrink-0">{{ $appointments->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <div x-data="{ show: $wire.entangle('showModal') }" x-show="show" style="display:none"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>
        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-lg bg-base-100 rounded-2xl shadow-2xl z-10 max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="card-body p-6 border-b border-base-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black">{{ $isEditing ? 'Editar Turno' : 'Nuevo Turno' }}</h3>
                        <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider mt-1">Configuración de
                            la cita</p>
                    </div>
                    <button type="button" @click="show = false" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="form-control">
                        <label class="label"><span
                                class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Cliente</span></label>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <select wire:model="client_id" class="select select-bordered select-sm w-full">
                                    <option value="">Invitado / Anónimo</option>
                                    @foreach($allClients as $cli)
                                        <option value="{{ $cli->id }}">{{ $cli->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if(!$client_id)
                                <div class="flex-1">
                                    <input type="text" wire:model="guest_name" placeholder="Nombre completo"
                                        class="input input-bordered input-sm w-full">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Barbero</span></label>
                            <select wire:model="selected_staff_id" class="select select-bordered select-sm">
                                <option value="">Selecciona...</option>
                                @foreach($staffMembers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('selected_staff_id') <span
                            class="text-error text-xs font-bold mt-1">Obligatorio</span> @enderror
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Estado</span></label>
                            <select wire:model="status" class="select select-bordered select-sm">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_progreso">En progreso</option>
                                <option value="completado">Completado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Fecha</span></label>
                            <input type="date" wire:model="date" class="input input-bordered input-sm">
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Inicio</span></label>
                            <input type="time" wire:model="start_time" class="input input-bordered input-sm">
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Fin
                                    (opc.)</span></label>
                            <input type="time" wire:model="end_time" class="input input-bordered input-sm">
                        </div>
                    </div>
                    <div class="form-control">
                        <label class="label"><span
                                class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Servicios</span></label>
                        <div class="grid grid-cols-2 gap-1 max-h-32 overflow-y-auto p-2 bg-base-200 rounded-lg">
                            @foreach($allServices as $svc)
                                <label class="flex items-center gap-2 p-1.5 hover:bg-base-300 rounded-lg cursor-pointer">
                                    <input type="checkbox" wire:model="selected_services" value="{{ $svc->id }}"
                                        class="checkbox checkbox-xs checkbox-primary">
                                    <span class="text-[11px] font-bold text-base-content/60">{{ $svc->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-control">
                        <label class="label"><span
                                class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Notas
                                Internas</span></label>
                        <textarea wire:model="notes" rows="2" class="textarea textarea-bordered textarea-sm"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="show = false" class="flex-1 btn btn-ghost">Cancelar</button>
                        <button type="submit" class="flex-1 btn btn-primary">
                            <span wire:loading.remove>Guardar Turno</span>
                            <span wire:loading>Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-data="{ show: $wire.entangle('appointmentToDelete') }" x-show="show" style="display:none"
        class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>
        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-sm bg-base-100 rounded-2xl shadow-2xl z-10 text-center p-8">
            <div class="alert alert-error justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                </svg>
            </div>
            <h3 class="text-xl font-black mb-2">¿Eliminar turno?</h3>
            <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider leading-relaxed">Se perderá toda
                la información de esta cita.</p>
            <div class="flex gap-3 mt-8">
                <button @click="show = false" class="flex-1 btn btn-ghost">Cancelar</button>
                <button wire:click="delete" class="flex-1 btn btn-error">Eliminar</button>
            </div>
        </div>
    </div>
</div>