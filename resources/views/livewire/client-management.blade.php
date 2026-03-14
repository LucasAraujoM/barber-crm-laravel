<div>
    <div class="space-y-4">

        {{-- ════ MÉTRICAS ════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            {{-- Total Clientes --}}
            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3 group">
                <div
                    class="w-10 h-10 rounded-xl bg-[#3B82F6]/10 text-[#3B82F6] flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Total Clientes</p>
                    <p class="text-2xl font-black text-[#1F2937] leading-none">{{ number_format($totalClients) }}</p>
                    <div
                        class="text-[10px] font-bold mt-1 {{ $clientsGrowth >= 0 ? 'text-emerald-400' : 'text-rose-500' }}">
                        {{ $clientsGrowth >= 0 ? '▲' : '▼' }} {{ abs($clientsGrowth) }}% <span
                            class="text-gray-500 font-normal">vs anterior</span>
                    </div>
                </div>
            </div>

            {{-- Nuevos este mes --}}
            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3 group">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <line x1="19" y1="8" x2="19" y2="14" />
                        <line x1="22" y1="11" x2="16" y2="11" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Nuevos este Mes</p>
                    <p class="text-2xl font-black text-[#1F2937] leading-none">{{ $newThisMonth }}</p>
                    <div class="text-[11px] text-gray-500 mt-1">Anterior: <span
                            class="font-bold text-gray-500">{{ $newLastMonth }}</span></div>
                </div>
            </div>

            {{-- Cortes este mes --}}
            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-3 group">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="6" cy="6" r="3" />
                        <circle cx="6" cy="18" r="3" />
                        <line x1="20" y1="4" x2="8.12" y2="15.88" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Cortes este Mes</p>
                    <p class="text-2xl font-black text-[#1F2937] leading-none">{{ $cutsThisMonth }}</p>
                    <div
                        class="text-[10px] font-bold mt-1 {{ $cutsGrowth >= 0 ? 'text-emerald-400' : 'text-rose-500' }}">
                        {{ $cutsGrowth >= 0 ? '▲' : '▼' }} {{ abs($cutsGrowth) }}% <span
                            class="text-gray-500 font-normal">vs anterior</span>
                    </div>
                </div>
            </div>

            {{-- Promedio cortes --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#E5E7EB] flex items-center gap-4 group">
                <div
                    class="w-12 h-12 rounded-xl bg-rose-100 text-rose-400 flex items-center justify-center shrink-0 group-hover:bg-rose-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Cortes / Cliente</p>
                    <p class="text-2xl font-black text-[#1F2937] leading-none">{{ $avgCutsPerClient }}</p>
                    <div class="text-[11px] text-gray-500 mt-1">Activos: <span
                            class="font-bold text-gray-500">{{ $activeThisMonth }}</span></div>
                </div>
            </div>
        </div>

        {{-- ════ GRÁFICOS ════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
            <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-[#1F2937]">Tendencia de Cortes y Clientes</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Últimos 6 meses</p>
                    </div>
                </div>
                <div class="relative h-52" wire:ignore>
                    <canvas id="clientTrendsChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-6 flex flex-col gap-4">
                <h2 class="text-base font-bold text-[#1F2937]">Comparativa Mensual</h2>
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
                            <span class="text-xs font-medium text-gray-500">{{ $comp['label'] }}</span>
                            <span
                                class="text-xs font-bold {{ $diff >= 0 ? 'text-emerald-400' : 'text-rose-500' }}">{{ $diff >= 0 ? '+' : '' }}{{ $diff }}</span>
                        </div>
                        <div class="relative h-2 bg-[#F5F7FA] rounded-full overflow-hidden mb-1">
                            <div class="absolute top-0 left-0 h-full bg-{{ $comp['color'] }}-200 rounded-full"
                                style="width: {{ $prevPct }}%"></div>
                            <div class="absolute top-0 left-0 h-full bg-{{ $comp['color'] }}-500 rounded-full"
                                style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-gray-500">
                            <span>Este: <strong>{{ $comp['current'] }}</strong></span>
                            <span>Anterior: <strong>{{ $comp['prev'] }}</strong></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ════ TABLA CON FILTROS ════ --}}
        <div class="bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] overflow-hidden">
            <div class="p-4 border-b border-[#E5E7EB] flex flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-wrap gap-4 items-center flex-1">
                    <div class="relative min-w-[280px] flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 w-4 h-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Buscar cliente por nombre, email o teléfono…"
                            class="w-full pl-9 pr-4 py-2.5 text-sm border border-[#E5E7EB] rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 bg-white transition-all">
                    </div>
                    <div class="flex gap-1 bg-white rounded-xl p-1 border border-[#E5E7EB]">
                        @foreach(['' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos', 'new' => 'Nuevos'] as $val => $label)
                            <button wire:click="$set('filter', '{{ $val }}')"
                                class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all {{ $filter === $val ? 'bg-white text-[#3B82F6] shadow-sm' : 'text-gray-500 hover:text-gray-600' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <button wire:click="openCreate"
                    class="px-5 py-2.5 bg-[#3B82F6] hover:bg-[#2563EB] text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-100">
                    + Nuevo Cliente
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-white text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-left">Contacto</th>
                            <th class="px-4 py-3 text-center">Cortes</th>
                            <th class="px-4 py-3 text-left">Último Turno</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($clients as $client)
                            @php
                                $lastAppt = $client->appointments->first();
                                if(!is_null($lastAppt)){
                                    $isRecent = $lastAppt && \Carbon\Carbon::parse($lastAppt->date)->gte(now()->startOfMonth());
                                    $isNewPlayer = $client->created_at->gte(now()->startOfMonth());
                                }else{ 
                                    $isRecent=false;
                                    $isNewPlayer=false;
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-[#1F2937] text-xs font-bold shadow-sm"
                                            style="background: {{ sprintf('hsl(%d, 60%%, 55%%)', crc32($client->name) % 360) }}">
                                            {{ strtoupper(substr($client->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#1F2937]">{{ $client->name }}</p>
                                            <p class="text-[10px] text-gray-500 font-medium truncate max-w-[150px]">
                                                {{ $client->notes ?? 'Sin notas' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <p class="text-xs font-bold text-gray-500">{{ $client->email ?? '—' }}</p>
                                        <p class="text-[10px] text-gray-500 font-medium">{{ $client->phone ?? '—' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full text-[10px] font-black {{ $client->appointments_count > 5 ? 'bg-indigo-100 text-indigo-700' : 'bg-[#F5F7FA] text-gray-500' }}">
                                        {{ $client->appointments_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @if($lastAppt)
                                        <p class="font-bold text-[#1F2937]">
                                            {{ \Carbon\Carbon::parse($lastAppt->date)->format('d/m/Y') }}</p>
                                        <p class="text-[10px] text-gray-500">
                                            {{ \Carbon\Carbon::parse($lastAppt->date)->diffForHumans() }}</p>
                                    @else
                                        <span class="text-gray-500 italic">Nunca</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($isNewPlayer)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tighter bg-emerald-100 text-emerald-700">★
                                            Nuevo</span>
                                    @elseif($isRecent)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tighter bg-[#3B82F6]/10 text-[#3B82F6]">Activo</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tighter bg-[#F5F7FA] text-gray-500">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-1.5">
                                        <button wire:click="openEdit({{ $client->id }})"
                                            class="p-1 bg-white text-gray-500 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $client->id }})"
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
                                <td colspan="6"
                                    class="px-6 py-12 text-center text-gray-500 font-bold uppercase tracking-widest text-xs">
                                    No se encontraron clientes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($clients instanceof \Illuminate\Pagination\LengthAwarePaginator && $clients->hasPages())
                <div class="px-4 py-3 border-t border-[#E5E7EB]">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>

        {{-- ════ MODAL CLIENTE ════ --}}
        <div x-data="{ show: $wire.entangle('showModal') }" x-show="show" style="display:none" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="show" x-transition.opacity class="absolute inset-0 bg-[#F5F7FA] backdrop-blur-sm"
                @click="show = false"></div>
            <div x-show="show" x-transition.scale
                class="relative w-full max-w-md bg-white rounded-[1.25rem] shadow-2xl z-10 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-5 py-4 border-b border-[#E5E7EB] flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-[#1F2937] tracking-tight">
                            {{ $isEditing ? 'Editar Cliente' : 'Nuevo Cliente' }}
                        </h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">Información de
                            contacto</p>
                    </div>
                    <button @click="show = false"
                        class="text-gray-500 hover:text-slate-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 overflow-y-auto custom-scrollbar">
                    <form wire:submit.prevent="save" class="space-y-4">
                        <div class="space-y-3">
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Nombre
                                    Completo</label>
                                <input type="text" wire:model="name"
                                    class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                @error('name') <span
                                class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Email</label>
                                <input type="email" wire:model="email"
                                    class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Teléfono</label>
                                <input type="text" wire:model="phone"
                                    class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Notas
                                    / Preferencias</label>
                                <textarea wire:model="notes" rows="3"
                                    class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all resize-none"></textarea>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="show = false"
                                class="flex-1 bg-white border-2 border-[#E5E7EB] text-gray-500 font-black py-4 rounded-2xl hover:bg-gray-50 transition-all text-xs uppercase tracking-widest">Cancelar</button>
                            <button type="submit"
                                class="flex-1 bg-[#3B82F6] hover:bg-[#2563EB] text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 text-xs uppercase tracking-widest">
                                Guardar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ════ MODAL ELIMINAR ════ --}}
        <div x-data="{ toDelete: $wire.entangle('clientToDelete') }" x-show="toDelete" style="display:none" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div x-show="toDelete" x-transition.opacity
                class="absolute inset-0 bg-[#1E293B]/60 backdrop-blur-sm" @click="toDelete = null"></div>
            <div x-show="toDelete" x-transition.scale
                class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl z-10 overflow-hidden text-center p-8">
                <div
                    class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-[#1F2937] mb-2">¿Eliminar cliente?</h3>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest leading-relaxed">Se perderá el
                    historial de turnos asociado a este cliente.</p>
                <div class="flex gap-3 mt-8">
                    <button @click="toDelete = null"
                        class="flex-1 bg-white text-gray-500 font-black py-3.5 rounded-xl hover:bg-gray-50 transition-all text-[10px] uppercase tracking-widest">Cancelar</button>
                    <button wire:click="delete"
                        class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-black py-3.5 rounded-xl transition-all shadow-lg shadow-rose-200 text-[10px] uppercase tracking-widest">Eliminar</button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function initClientCharts() {
                const labels = @json(array_column($chartData, 'label'));
                const cuts = @json(array_column($chartData, 'cuts'));
                const clients = @json(array_column($chartData, 'clients'));
                const canvas = document.getElementById('clientTrendsChart');
                if (!canvas) return;

                const ctx = canvas.getContext('2d');

                if (window.myClientTrendsChart) window.myClientTrendsChart.destroy();

                window.myClientTrendsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Cortes',
                                data: cuts,
                                borderColor: '#3B82F6',
                                borderWidth: 3,
                                pointBackgroundColor: '#3B82F6',
                                tension: 0.4,
                                fill: false,
                            },
                            {
                                label: 'Nuevos',
                                data: clients,
                                borderColor: '#10B981',
                                borderWidth: 3,
                                pointBackgroundColor: '#10B981',
                                tension: 0.4,
                                fill: false,
                            }
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: '#1F2937', titleColor: '#ffffff', bodyColor: '#ffffff', padding: 12, cornerRadius: 8 },
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#6B7280', font: { size: 10 } } },
                            y: { beginAtZero: true, grid: { color: '#E5E7EB' }, ticks: { color: '#6B7280', font: { size: 10 }, precision: 0 } },
                        },
                    },
                });
            }

            document.addEventListener('livewire:navigated', initClientCharts);
            document.addEventListener('DOMContentLoaded', initClientCharts);
        </script>
    @endpush
</div>