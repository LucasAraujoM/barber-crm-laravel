<div>
    <div class="space-y-4">

        {{-- ════ HEADER ════ --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-[#1F2937]">Gestión de Barberos</h1>
                <p class="text-xs text-gray-500 mt-0.5">Rendimiento y actividad del equipo</p>
            </div>
            <button wire:click="openCreate"
                class="inline-flex items-center px-4 py-2 bg-[#3B82F6] border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-[#2563EB] shadow-md cursor-pointer transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Miembro
            </button>
        </div>

        {{-- ════ MÉTRICAS ════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            {{-- Total Barberos --}}
            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="5" />
                        <path d="M20 21a8 8 0 1 0-16 0" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Total Barberos</p>
                    <p class="text-xl font-black text-[#1F2937] leading-none">{{ $totalStaff }}</p>
                    <div class="text-[10px] text-gray-500 mt-1">Activos: <span
                            class="font-bold text-gray-500">{{ $activeThisMonth }}</span></div>
                </div>
            </div>

            {{-- Cortes este mes --}}
            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="6" cy="6" r="3" />
                        <circle cx="6" cy="18" r="3" />
                        <line x1="20" y1="4" x2="8.12" y2="15.88" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Cortes este Mes
                    </p>
                    <p class="text-xl font-black text-[#1F2937] leading-none">{{ $cutsThisMonth }}</p>
                    <div
                        class="text-[10px] font-bold mt-1 {{ $cutsGrowth >= 0 ? 'text-emerald-400' : 'text-rose-500' }}">
                        {{ $cutsGrowth >= 0 ? '▲' : '▼' }} {{ abs($cutsGrowth) }}% <span
                            class="text-gray-500 font-normal">vs anterior</span>
                    </div>
                </div>
            </div>

            {{-- Promedio --}}
            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-[#3B82F6]/10 text-[#3B82F6] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                        <line x1="18" y1="20" x2="18" y2="10" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Promedio / Barbero
                    </p>
                    <p class="text-xl font-black text-[#1F2937] leading-none">{{ $avgCutsPerStaff }}</p>
                    <div class="text-[10px] text-gray-500 mt-1">Total: <span
                            class="font-bold text-gray-500">{{ $cutsThisMonth }}</span></div>
                </div>
            </div>

            {{-- Top Barbero --}}
            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Top Barbero</p>
                    <p class="text-sm font-black text-[#1F2937] leading-tight truncate mt-0.5">
                        {{ $topStaff->name ?? '—' }}
                    </p>
                    <p class="text-[9px] text-gray-500 mt-1 uppercase font-bold tracking-tighter">Más cortes este mes
                    </p>
                </div>
            </div>
        </div>

        {{-- ════ GRÁFICOS ════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-3">
            <div class="xl:col-span-2 bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] p-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-[#1F2937]">Cortes por Mes</h2>
                        <p class="text-[10px] text-gray-500 mt-0.5">Últimos 6 meses — todo el equipo</p>
                    </div>
                </div>
                <div class="relative h-[250px]" wire:ignore>
                    <canvas id="staffChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] p-4 flex flex-col gap-4">
                <h2 class="text-base font-bold text-[#1F2937]">Comparativa Mensual</h2>

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
                            <span class="text-xs font-medium text-gray-500">{{ $comp['label'] }}</span>
                            <span class="text-xs font-bold {{ $diff >= 0 ? 'text-emerald-400' : 'text-rose-500' }}">
                                {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                            </span>
                        </div>
                        <div class="relative h-2 bg-[#F5F7FA] rounded-full overflow-hidden mb-1">
                            <div class="absolute top-0 left-0 h-full bg-{{ $comp['color'] }}-200 rounded-full"
                                style="width: {{ $prevPct }}%"></div>
                            <div class="absolute top-0 left-0 h-full bg-{{ $comp['color'] }}-500 rounded-full"
                                style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-gray-500">
                            <span>Este mes: <strong class="text-[#1F2937]">{{ $comp['current'] }}</strong></span>
                            <span>Anterior: <strong class="text-[#1F2937]">{{ $comp['prev'] }}</strong></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ════ TABLA ════ --}}
        <div class="bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] overflow-hidden">
            <div class="px-4 py-3 border-b border-[#E5E7EB] flex items-center justify-between">
                <h2 class="text-sm font-bold text-[#1F2937] uppercase tracking-widest">Equipo de Barberos</h2>
                <span class="text-[10px] text-gray-500">{{ $totalStaff }} registrados</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-white text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            <th class="px-4 py-3 text-left">Barbero</th>
                            <th class="px-4 py-3 text-left">Contacto</th>
                            <th class="px-4 py-3 text-center">Rol</th>
                            <th class="px-4 py-3 text-center">Cortes (Total)</th>
                            <th class="px-4 py-3 text-center">Este Mes</th>
                            <th class="px-4 py-3 text-center">Tendencia</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($staff as $member)
                            @php $trend = $member->cuts_this_month - $member->cuts_last_month; @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($member->avatar)
                                            <img class="w-8 h-8 rounded-full object-cover shadow-sm ring-2 ring-white"
                                                src="/media/{{ $member->avatar }}" alt="">
                                        @else
                                            <div
                                                class="w-8 h-8 rounded-full bg-[#3B82F6]/10 text-[#3B82F6] flex items-center justify-center font-black text-xs shadow-sm ring-2 ring-white">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-[#1F2937]">{{ $member->name }}</p>
                                            <p class="text-[10px] text-gray-500 font-medium truncate max-w-[120px]">
                                                {{ $member->notes ?? 'Sin notas' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <p class="text-xs font-bold text-gray-500">{{ $member->email ?? '—' }}</p>
                                        <p class="text-[10px] text-gray-500 font-medium">{{ $member->phone ?? '—' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $member->role === 'Admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-[#F5F7FA] text-gray-500' }}">
                                        {{ $member->role ?? 'Staff' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-sm font-black text-[#1F2937]">{{ $member->appointments_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-sm font-black text-[#3B82F6]">{{ $member->cuts_this_month }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($trend > 0)
                                        <span
                                            class="text-[10px] font-black text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full">▲
                                            +{{ $trend }}</span>
                                    @elseif($trend < 0)
                                        <span class="text-[10px] font-black text-rose-500 bg-rose-500/10 px-2 py-0.5 rounded-full">▼
                                            {{ $trend }}</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-1.5">
                                        <button wire:click="openEdit({{ $member->id }})"
                                            class="p-1 bg-white text-gray-500 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $member->id }})"
                                            class="p-1 bg-white text-gray-500 rounded-lg hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-4 py-8 text-center text-gray-500 font-bold uppercase tracking-widest text-xs">
                                    No hay barberos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ════ MODAL AGREGAR/EDITAR ════ --}}
        <div x-data="{ show: $wire.entangle('showModal') }" x-show="show" style="display:none"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="show" x-transition.opacity class="absolute inset-0 bg-[#F5F7FA] backdrop-blur-sm"
                @click="show = false"></div>
            <div x-show="show" x-transition.scale
                class="relative w-full max-w-lg bg-white rounded-[1.25rem] shadow-2xl z-10 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-5 py-4 border-b border-[#E5E7EB] flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-[#1F2937] tracking-tight">
                            {{ $isEditing ? 'Editar Barbero' : 'Nuevo Barbero' }}
                        </h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">Completa los
                            datos
                            del equipo</p>
                    </div>
                    <button @click="show = false" class="text-gray-500 hover:text-slate-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 overflow-y-auto custom-scrollbar">
                    <form wire:submit.prevent="save" class="space-y-4">
                        <div class="flex justify-center mb-4">
                            <div class="relative group">
                                <label class="cursor-pointer block">
                                    <div
                                        class="w-24 h-24 rounded-full border-4 border-[#E5E7EB] shadow-md ring-2 ring-indigo-500/20 overflow-hidden bg-white flex items-center justify-center">
                                        @if($avatar)
                                            <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @elseif($avatarPreview)
                                            <img src="/media/{{ $avatarPreview }}" class="w-full h-full object-cover">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                                <path
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <input type="file" wire:model="avatar" class="hidden">
                                </label>
                                <div
                                    class="absolute -bottom-1 -right-1 bg-white p-1.5 rounded-full shadow-lg border border-[#E5E7EB]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                        class="text-[#3B82F6]">
                                        <path
                                            d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                                        <circle cx="12" cy="13" r="4" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Nombre
                                    Completo</label>
                                <input type="text" wire:model="name"
                                    class="w-full bg-white border border-[#E5E7EB] rounded-lg py-2 px-3 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                @error('name') <span
                                class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Email</label>
                                    <input type="email" wire:model="email"
                                        class="w-full bg-white border border-[#E5E7EB] rounded-lg py-2 px-3 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Teléfono</label>
                                    <input type="text" wire:model="phone"
                                        class="w-full bg-white border border-[#E5E7EB] rounded-lg py-2 px-3 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Rol</label>
                                    <select wire:model="role"
                                        class="w-full bg-white border border-[#E5E7EB] rounded-lg py-2 px-3 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all text-black">
                                        <option value="Staff">Personal</option>
                                        <option value="Manager">Gerente</option>
                                        <option value="Admin">Administrador</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Notas</label>
                                    <input type="text" wire:model="notes"
                                        class="w-full bg-white border border-[#E5E7EB] rounded-lg py-2 px-3 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="show = false"
                                class="flex-1 bg-white border border-[#E5E7EB] text-gray-500 font-bold py-2.5 rounded-lg hover:bg-gray-50 transition-all text-[10px] uppercase tracking-widest">Cancelar</button>
                            <button type="submit"
                                class="flex-1 bg-[#3B82F6] hover:bg-[#2563EB] text-white font-bold py-2.5 rounded-lg transition-all shadow-md text-[10px] uppercase tracking-widest">
                                <span wire:loading.remove>Guardar Barbero</span>
                                <span wire:loading>Procesando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ════ MODAL ELIMINAR ════ --}}
        <div x-data="{ toDelete: $wire.entangle('staffToDelete') }" x-show="toDelete" style="display:none"
            class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div x-show="toDelete" x-transition.opacity class="absolute inset-0 bg-[#F5F7FA] backdrop-blur-sm"
                @click="toDelete = null"></div>
            <div x-show="toDelete" x-transition.scale
                class="relative w-full max-w-sm bg-white rounded-[1.25rem] shadow-2xl z-10 overflow-hidden text-center p-6">
                <div
                    class="w-12 h-12 bg-rose-500/10 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-[#1F2937] mb-1.5">¿Eliminar barbero?</h3>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest leading-relaxed">Esta acción
                    borrará
                    al miembro y su historial de la base de datos.</p>
                <div class="flex gap-3 mt-6">
                    <button @click="toDelete = null"
                        class="flex-1 bg-white text-gray-500 font-bold py-2.5 rounded-lg hover:bg-gray-50 transition-all text-[10px] uppercase tracking-widest">Cancelar</button>
                    <button wire:click="delete"
                        class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-bold py-2.5 rounded-lg transition-all shadow-md text-[10px] uppercase tracking-widest">Eliminar
                        Ahora</button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function initStaffChart() {
                const labels = @json(array_column($chartData, 'label'));
                const counts = @json(array_column($chartData, 'cuts'));
                const canvas = document.getElementById('staffChart');
                if (!canvas) return;

                const ctx = canvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 180);
                gradient.addColorStop(0, 'rgba(139, 92, 246, 0.35)');
                gradient.addColorStop(1, 'rgba(139, 92, 246, 0)');

                if (window.myStaffChart) window.myStaffChart.destroy();

                window.myStaffChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            data: counts,
                            backgroundColor: counts.map((_, i) => i === counts.length - 1 ? '#8b5cf6' : 'rgba(139, 92, 246, 0.4)'),
                            borderRadius: 6,
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
                                padding: 10,
                                cornerRadius: 8,
                                callbacks: { label: ctx => ` ${ctx.parsed.y} cortes` },
                            },
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 10 } } },
                            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { color: '#9ca3af', font: { size: 10 }, precision: 0 } },
                        },
                    },
                });
            }

            document.addEventListener('livewire:navigated', initStaffChart);
            document.addEventListener('DOMContentLoaded', initStaffChart);
        </script>
    @endpush
</div>