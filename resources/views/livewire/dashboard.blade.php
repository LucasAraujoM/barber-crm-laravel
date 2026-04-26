<div class="flex flex-col h-full gap-4 min-h-0">

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 shrink-0">

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="badge badge-primary badge-lg opacity-90 text-primary-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-base-content/50 uppercase tracking-wider">Turnos Hoy</p>
                    <p class="text-2xl font-black">{{ $turnosHoy }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="badge badge-info badge-lg opacity-90 text-info-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18" />
                        <path d="m19 9-5 5-4-4-3 3" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-base-content/50 uppercase tracking-wider">Semana</p>
                    <p class="text-2xl font-black">{{ $turnosSemana }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="badge badge-success badge-lg opacity-90 text-success-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-base-content/50 uppercase tracking-wider">Clientes</p>
                    <p class="text-2xl font-black">{{ number_format($totalClients) }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-primary text-primary-content border border-primary">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0 overflow-hidden">
                    <p class="text-[10px] font-black opacity-70 uppercase tracking-wider">Próximo</p>
                    @if($nextAppointment)
                        <p class="text-sm font-bold truncate leading-tight">
                            {{ $nextAppointment->start_time ? \Carbon\Carbon::parse($nextAppointment->start_time)->format('H:i') : '' }}
                            —
                            {{ $nextAppointment->client_id ? $nextAppointment->client->name : $nextAppointment->guest_name }}
                        </p>
                    @else
                        <p class="text-sm font-bold">Sin turnos</p>
                    @endif
                </div>
            </div>
        </div>

        <button type="button" wire:click="resetModal"
            class="card bg-primary text-primary-content border border-primary hover:bg-primary-focus transition-all cursor-pointer">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black opacity-70 uppercase tracking-wider">Agendar</p>
                    <p class="text-sm font-black leading-tight">Turno Rápido</p>
                </div>
            </div>
        </button>
    </div>

    <div class="flex-1 grid grid-cols-1 xl:grid-cols-4 gap-4 min-h-0">

        <div class="xl:col-span-1 flex flex-col min-h-0">
            <div class="card bg-base-100 border border-base-300 flex flex-col flex-1 min-h-0">
                <div class="card-body flex-none p-4 pb-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold">Agenda de Hoy</h2>
                            <p class="text-[10px] text-base-content/50 font-bold uppercase tracking-wider">
                                {{ now()->translatedFormat('l, d \d\e F') }}
                            </p>
                        </div>
                        <span class="badge badge-primary badge-sm">{{ $turnosHoy }}</span>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar divide-y divide-base-300 min-h-0 px-1 pb-2">
                    @forelse($appointments as $appt)
                        @php
                            $startTime = $appt->start_time ? \Carbon\Carbon::parse($appt->start_time) : null;
                            $endTime = $appt->end_time ? \Carbon\Carbon::parse($appt->end_time) : ($startTime ? $startTime->copy()->addHour() : null);
                            $isPast = $startTime && $startTime->isPast() && $endTime && $endTime->isPast();
                            $isNow = $startTime && $startTime->isPast() && $endTime && $endTime->isFuture();
                            $clientName = $appt->client_id ? ($appt->client->name ?? 'Cliente') : ($appt->guest_name ?? 'Invitado');
                            $statusColor = 'badge-ghost';
                            if ($appt->status === 'completado')
                                $statusColor = 'badge-success';
                            elseif ($appt->status === 'cancelado')
                                $statusColor = 'badge-error';
                            elseif ($appt->status === 'en_progreso' || $isNow)
                                $statusColor = 'badge-warning';
                            elseif (!$isPast)
                                $statusColor = 'badge-primary';
                        @endphp
                        <div
                            class="group flex items-center gap-3 px-3 py-3 hover:bg-base-200/50 transition-all {{ $isPast && $appt->status !== 'en_progreso' ? 'opacity-50' : '' }}">
                            <div class="text-center w-10 shrink-0">
                                <p class="text-sm font-black">{{ $startTime ? $startTime->format('H:i') : '—' }}</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold truncate">{{ $clientName }}</p>
                                    @if($isNow && $appt->status === 'pendiente')
                                        <span class="relative flex h-2 w-2">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-warning"></span>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-base-content/50 font-semibold truncate leading-tight">
                                    <span class="text-primary">{{ $appt->staff->name ?? '—' }}</span>
                                    @if($appt->notes) · {{ $appt->notes }} @endif
                                </p>
                                @if($appt->services->count() > 0)
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($appt->services as $s)
                                            <span class="badge badge-outline badge-xs">{{ $s->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                @if($isNow && $appt->status !== 'completado')
                                    <button wire:click="completeAppointment({{ $appt->id }})"
                                        class="btn btn-xs btn-success btn-circle" title="Completar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </button>
                                @endif
                                <button type="button" wire:click="editAppointment({{ $appt->id }})"
                                    class="btn btn-xs btn-ghost btn-circle" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center flex flex-col items-center gap-3">
                            <div class="w-12 h-12 bg-base-200 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                            </div>
                            <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider">No hay turnos para
                                hoy</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="xl:col-span-3 flex flex-col min-h-0">
            <div class="card bg-base-100 border border-base-300 flex flex-col flex-1 min-h-0">
                <div class="card-body flex-none p-4 pb-2">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-bold">Calendario de Turnos</h2>
                        <div class="badge badge-sm gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-primary"></span> Confirmado
                        </div>
                    </div>
                </div>
                <div class="p-2 flex-1 min-h-0 flex flex-col" wire:ignore>
                    <x-calendar :appointments="$calendarAppointments" />
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ show: @entangle('showQuickModal') }" x-show="show" style="display:none"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4">

        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false">
        </div>

        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-2xl bg-base-100 rounded-2xl shadow-2xl z-10 flex flex-col max-h-[90vh]">

            <form wire:submit.prevent="saveAppointment" class="flex flex-col min-h-0 flex-1">

                <div class="card-body p-6 pb-4 border-b border-base-300 shrink-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black">{{ $editing_id ? 'Editar Turno' : 'Nuevo Turno' }}</h3>
                            @if($allStaff->count() == 0)
                                <p class="text-xs text-error font-bold uppercase tracking-wider mt-1">
                                    Sin barberos — <a href="{{ route('staff') }}" wire:navigate class="underline">Registrar
                                        uno</a>
                                </p>
                            @else
                                <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider mt-1">
                                    {{ $editing_id ? 'Actualizando datos' : 'Agregado al instante' }}
                                </p>
                            @endif
                        </div>
                        <button type="button" @click="show = false" class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar px-6 py-4 space-y-4 min-h-0">

                    <div class="grid grid-cols-3 gap-3">
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Fecha</span></label>
                            <input type="date" wire:model="date" required
                                class="input input-bordered input-sm text-center">
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Inicio</span></label>
                            <input type="time" wire:model="start_time" required
                                class="input input-bordered input-sm text-center">
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Fin</span></label>
                            <input type="time" wire:model="end_time" class="input input-bordered input-sm text-center">
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label"><span
                                class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Barbero</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($allStaff as $st)
                                <button type="button" wire:click="$set('selected_staff_id', {{ $st->id }})"
                                    class="flex flex-col items-center p-3 border-2 rounded-xl transition-all cursor-pointer
                                                            {{ $selected_staff_id == $st->id ? 'border-primary bg-primary/10' : 'border-base-300 hover:border-primary/40 hover:bg-base-200' }}">
                                    <div
                                        class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-xs mb-1">
                                        {{ strtoupper(substr($st->name, 0, 2)) }}
                                    </div>
                                    <span class="text-[10px] font-black truncate w-full text-center">{{ $st->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div x-data="{ isGuest: false }" @init-edit-modal.window="isGuest = $event.detail.isGuest">
                        <label class="label"><span
                                class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Cliente</span></label>
                        <div class="tabs tabs-boxed mb-2">
                            <a class="tab tab-sm" :class="!isGuest && 'tab-active'"
                                @click="isGuest = false">Registrado</a>
                            <a class="tab tab-sm" :class="isGuest && 'tab-active'" @click="isGuest = true">Invitado</a>
                        </div>
                        <div x-show="!isGuest">
                            <select wire:model="client_id" class="select select-bordered select-sm w-full">
                                <option value="">Seleccionar cliente...</option>
                                @foreach($allClients as $cli)
                                    <option value="{{ $cli->id }}">{{ $cli->name }} ({{ $cli->phone ?? 'Sin tel.' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div x-show="isGuest">
                            <input type="text" wire:model="guest_name" placeholder="Nombre del invitado"
                                class="input input-bordered input-sm w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Servicios</span></label>
                            <div class="space-y-1 max-h-32 overflow-y-auto p-2 bg-base-200 rounded-lg">
                                @foreach($allServices as $svc)
                                    <label class="flex items-center gap-2 p-2 hover:bg-base-300 rounded-lg cursor-pointer">
                                        <input type="checkbox" wire:model="selected_services" value="{{ $svc->id }}"
                                            class="checkbox checkbox-sm checkbox-primary">
                                        <div class="min-w-0">
                                            <span
                                                class="text-[10px] font-black uppercase block truncate">{{ $svc->name }}</span>
                                            <span
                                                class="text-[9px] text-base-content/40">${{ number_format($svc->price, 0, ',', '.') }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Estado
                                    y Notas</span></label>
                            <select wire:model="status" class="select select-bordered select-sm">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_progreso">En Progreso</option>
                                <option value="completado">Completado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                            <textarea wire:model="notes" rows="3" placeholder="Notas adicionales..."
                                class="textarea textarea-bordered textarea-sm mt-2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-body p-6 pt-4 border-t border-base-300 flex gap-3 shrink-0">
                    @if($editing_id)
                        <button type="button" wire:click="deleteAppointment({{ $editing_id }})"
                            class="btn btn-error">Eliminar</button>
                    @endif
                    <div class="flex-1"></div>
                    <button type="button" @click="show = false" class="btn btn-ghost">Cancelar</button>
                    <button type="submit"
                        class="btn btn-primary">{{ $editing_id ? 'Actualizar' : 'Confirmar Turno' }}</button>
                </div>

            </form>
        </div>
    </div>

</div>