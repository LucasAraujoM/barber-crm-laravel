@extends('layout.app')

@section('title', 'Turnos')

@section('content')
    <div class="space-y-6">

        {{-- HEADER & METRICS --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">Historial de Turnos</h1>
                <p class="text-sm text-gray-400 mt-1">Gestión completa de citas y agenda</p>
            </div>
            <div class="flex gap-3 overflow-x-auto pb-2 md:pb-0">
                <div
                    class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 min-w-[140px]">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Hoy</p>
                        <p class="text-sm font-bold text-gray-800">{{ $todayAppointments }}</p>
                    </div>
                </div>
                <div
                    class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 min-w-[140px]">
                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path d="M3 3v18h18" />
                            <path d="m19 9-5 5-4-4-3 3" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Semana</p>
                        <p class="text-sm font-bold text-gray-800">{{ $upcomingWeek }}</p>
                    </div>
                </div>
                <div
                    class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 min-w-[140px]">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Mes</p>
                        <p class="text-sm font-bold text-gray-800">{{ $monthAppointments }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('appointments.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="date_from"
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Desde</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>
                <div>
                    <label for="date_to"
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Hasta</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>
                <div>
                    <label for="staff_id"
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Barbero</label>
                    <select name="staff_id" id="staff_id"
                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="">Todos</option>
                        @foreach($staffMembers as $staff)
                            <option value="{{ $staff->id }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm shadow-indigo-200">
                        Filtrar
                    </button>
                    <a href="{{ route('appointments.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg text-sm font-medium transition-colors">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3">Fecha y Hora</th>
                            <th class="px-6 py-3">Cliente</th>
                            <th class="px-6 py-3">Barbero</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3 w-1/4">Notas</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($appointments as $appt)
                            @php
                                $start = \Carbon\Carbon::parse($appt->start_time);
                                $isPast = $start->isPast();
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800">{{ $start->format('d/m/Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $start->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($appt->end_time)->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ optional($appt->client)->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                        {{ optional($appt->staff)->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($isPast)
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            Completado
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-600 border border-sky-100">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $appt->notes }}">
                                    {{ $appt->notes ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('appointments.destroy', $appt->id) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar este turno?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-gray-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors group-hover:text-gray-400"
                                            title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M3 6h18" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p>No se encontraron turnos con los filtros seleccionados.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($appointments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection