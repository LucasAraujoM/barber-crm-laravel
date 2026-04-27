<div class="flex flex-col h-full min-h-0">
    <div class="space-y-4 overflow-y-auto custom-scrollbar flex-1 min-h-0 pr-1">

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-3">
                    <div class="badge badge-primary badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Total Clientes
                        </p>
                        <p class="text-2xl font-black">{{ number_format($totalClients) }}</p>
                        <div class="text-[10px] font-bold {{ $clientsGrowth >= 0 ? 'text-success' : 'text-error' }}">
                            {{ $clientsGrowth >= 0 ? '▲' : '▼' }} {{ abs($clientsGrowth) }}% <span
                                class="text-base-content/40 font-normal">vs anterior</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-3">
                    <div class="badge badge-success badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <line x1="19" y1="8" x2="19" y2="14" />
                            <line x1="22" y1="11" x2="16" y2="11" />
                        </svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Nuevos este Mes
                        </p>
                        <p class="text-2xl font-black">{{ $newThisMonth }}</p>
                        <div class="text-[11px] text-base-content/50">Anterior: <span
                                class="font-bold">{{ $newLastMonth }}</span></div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-3">
                    <div class="badge badge-warning badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="6" cy="6" r="3" />
                            <circle cx="6" cy="18" r="3" />
                            <line x1="20" y1="4" x2="8.12" y2="15.88" />
                        </svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Cortes este Mes
                        </p>
                        <p class="text-2xl font-black">{{ $cutsThisMonth }}</p>
                        <div class="text-[10px] font-bold {{ $cutsGrowth >= 0 ? 'text-success' : 'text-error' }}">
                            {{ $cutsGrowth >= 0 ? '▲' : '▼' }} {{ abs($cutsGrowth) }}% <span
                                class="text-base-content/40 font-normal">vs anterior</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-3">
                    <div class="badge badge-error badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                        </svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Cortes / Cliente
                        </p>
                        <p class="text-2xl font-black">{{ $avgCutsPerClient }}</p>
                        <div class="text-[11px] text-base-content/50">Activos: <span
                                class="font-bold">{{ $activeThisMonth }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <div class="xl:col-span-2 card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="card-title text-sm">
                        <h2>Tendencia de Cortes y Clientes</h2>
                        <p class="text-xs text-base-content/50 font-normal normal-case">Últimos 6 meses</p>
                    </div>
                    <div class="relative h-52" wire:ignore>
                        <canvas id="clientTrendsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title text-sm">Comparativa Mensual</h2>
                    @php
                        $comparisons = [
                            ['label' => 'Nuevos Clientes', 'current' => $newThisMonth, 'prev' => $newLastMonth, 'color' => 'primary'],
                            ['label' => 'Cortes Realizados', 'current' => $cutsThisMonth, 'prev' => $cutsLastMonth, 'color' => 'warning'],
                            ['label' => 'Clientes Activos', 'current' => $activeThisMonth, 'prev' => $activeLastMonth, 'color' => 'success'],
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
                                <span class="text-xs font-medium text-base-content/60">{{ $comp['label'] }}</span>
                                <span
                                    class="text-xs font-bold {{ $diff >= 0 ? 'text-success' : 'text-error' }}">{{ $diff >= 0 ? '+' : '' }}{{ $diff }}</span>
                            </div>
                            <progress class="progress progress-{{ $comp['color'] }}" value="{{ $pct }}"
                                max="100"></progress>
                            <div class="flex justify-between text-[10px] text-base-content/40 mt-1">
                                <span>Este: <strong>{{ $comp['current'] }}</strong></span>
                                <span>Anterior: <strong>{{ $comp['prev'] }}</strong></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 overflow-hidden">
            <div class="card-body p-4 pb-0">
                <div class="flex flex-wrap gap-4 items-center justify-between">
                    <div class="flex flex-wrap gap-4 items-center flex-1">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar cliente..."
                            class="input input-bordered input-sm w-full max-w-xs">
                        <div class="join">
                            @foreach(['' => 'Todos', 'active' => 'Activos', 'inactive' => 'Inactivos', 'new' => 'Nuevos'] as $val => $label)
                                <button wire:click="$set('filter', '{{ $val }}')"
                                    class="join-item btn btn-sm {{ $filter === $val ? 'btn-primary' : 'btn-ghost' }}">{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>
                    <button wire:click="openCreate" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Nuevo Cliente
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold">Cliente</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold">Contacto
                            </th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">
                                Cortes</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold">Último Turno
                            </th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">
                                Estado</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            @php
                                $lastAppt = $client->appointments->first();
                                $isRecent = $lastAppt && \Carbon\Carbon::parse($lastAppt->date)->gte(now()->startOfMonth());
                                $isNewPlayer = $client->created_at ? $client->created_at->gte(now()->startOfMonth()) : false;
                            @endphp
                            <tr class="hover:bg-base-200/50">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="w-8 rounded-xl text-xs font-bold flex items-center justify-center"
                                                style="background: {{ sprintf('hsl(%d, 60%%, 55%%)', crc32($client->name) % 360) }}; color: white;">
                                                <span>{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm">{{ $client->name }}</p>
                                            <p class="text-[10px] text-base-content/50 truncate max-w-[150px]">
                                                {{ $client->notes ?? 'Sin notas' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-xs">
                                        <p class="font-semibold text-base-content/60">{{ $client->email ?? '—' }}</p>
                                        <p class="text-[10px]">{{ $client->phone ?? '—' }}</p>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge badge-sm {{ $client->appointments_count > 5 ? 'badge-primary' : 'badge-ghost' }}">{{ $client->appointments_count }}</span>
                                </td>
                                <td class="text-xs">
                                    @if($lastAppt)
                                        <p class="font-bold">{{ \Carbon\Carbon::parse($lastAppt->date)->format('d/m/Y') }}</p>
                                        <p class="text-[10px] text-base-content/50">
                                            {{ \Carbon\Carbon::parse($lastAppt->date)->diffForHumans() }}</p>
                                    @else
                                        <span class="text-base-content/40 italic">Nunca</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($isNewPlayer)
                                        <span class="badge badge-success badge-sm">★ Nuevo</span>
                                    @elseif($isRecent)
                                        <span class="badge badge-primary badge-sm">Activo</span>
                                    @else
                                        <span class="badge badge-ghost badge-sm">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-1">
                                        <button wire:click="openEdit({{ $client->id }})"
                                            class="btn btn-xs btn-ghost btn-circle text-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $client->id }})"
                                            class="btn btn-xs btn-ghost btn-circle text-error">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
                                    class="text-center text-base-content/50 font-bold uppercase tracking-wider text-xs py-8">
                                    No se encontraron clientes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($clients instanceof \Illuminate\Pagination\LengthAwarePaginator && $clients->hasPages())
                <div class="card-body p-4 pt-0">{{ $clients->links() }}</div>
            @endif
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
                class="relative w-full max-w-md bg-base-100 rounded-2xl shadow-2xl z-10 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="card-body p-5 border-b border-base-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black">{{ $isEditing ? 'Editar Cliente' : 'Nuevo Cliente' }}</h3>
                            <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider mt-1">Información
                                de contacto</p>
                        </div>
                        <button type="button" @click="show = false" class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </div>
                </div>
                <div class="p-5 overflow-y-auto custom-scrollbar">
                    <form wire:submit.prevent="save" class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Nombre
                                    Completo</span></label>
                            <input type="text" wire:model="name" class="input input-bordered" placeholder="Nombre">
                            @error('name') <span class="text-error text-xs font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Email</span></label>
                            <input type="email" wire:model="email" class="input input-bordered"
                                placeholder="email@ejemplo.com">
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Teléfono</span></label>
                            <input type="text" wire:model="phone" class="input input-bordered" placeholder="+54 ...">
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Notas
                                    / Preferencias</span></label>
                            <textarea wire:model="notes" rows="3" class="textarea textarea-bordered"
                                placeholder="Preferencias del cliente..."></textarea>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="show = false" class="flex-1 btn btn-ghost">Cancelar</button>
                            <button type="submit"
                                class="flex-1 btn btn-primary">{{ $isEditing ? 'Actualizar' : 'Guardar Cliente' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-data="{ show: $wire.entangle('clientToDelete') }" x-show="show" style="display:none"
            class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>
            <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-sm bg-base-100 rounded-2xl shadow-2xl z-10 text-center p-8">
                <div class="alert alert-error justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                    </svg>
                </div>
                <h3 class="text-xl font-black mb-2">¿Eliminar cliente?</h3>
                <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider leading-relaxed">Se perderá el
                    historial de turnos asociado.</p>
                <div class="flex gap-3 mt-6">
                    <button @click="show = false" class="flex-1 btn btn-ghost">Cancelar</button>
                    <button wire:click="delete" class="flex-1 btn btn-error">Eliminar</button>
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
                            { label: 'Cortes', data: cuts, borderColor: '#3b82f6', borderWidth: 3, pointBackgroundColor: '#3b82f6', tension: 0.4, fill: false },
                            { label: 'Nuevos', data: clients, borderColor: '#10b981', borderWidth: 3, pointBackgroundColor: '#10b981', tension: 0.4, fill: false },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1F2937', padding: 12, cornerRadius: 8 } },
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