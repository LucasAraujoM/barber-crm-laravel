<div class="flex flex-col h-full min-h-0">
    <div class="space-y-4 overflow-y-auto custom-scrollbar flex-1 min-h-0 pr-1">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold">Gestión de Barberos</h1>
                <p class="text-xs text-base-content/50 mt-0.5">Rendimiento y actividad del equipo</p>
            </div>
            <button wire:click="openCreate" class="btn btn-primary btn-sm gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                Agregar Miembro
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-3">
                    <div class="badge badge-secondary badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5" /><path d="M20 21a8 8 0 1 0-16 0" /></svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Total Barberos</p>
                        <p class="text-xl font-black">{{ $totalStaff }}</p>
                        <div class="text-[10px] text-base-content/50">Activos: <span class="font-bold">{{ $activeThisMonth }}</span></div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-3">
                    <div class="badge badge-warning badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="3" /><circle cx="6" cy="18" r="3" /><line x1="20" y1="4" x2="8.12" y2="15.88" /></svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Cortes este Mes</p>
                        <p class="text-xl font-black">{{ $cutsThisMonth }}</p>
                        <div class="text-[10px] font-bold {{ $cutsGrowth >= 0 ? 'text-success' : 'text-error' }}">{{ $cutsGrowth >= 0 ? '▲' : '▼' }} {{ abs($cutsGrowth) }}% vs anterior</div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-3">
                    <div class="badge badge-primary badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="20" x2="12" y2="4" /><line x1="6" y1="20" x2="6" y2="14" /><line x1="18" y1="20" x2="18" y2="10" /></svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Promedio / Barbero</p>
                        <p class="text-xl font-black">{{ $avgCutsPerStaff }}</p>
                        <div class="text-[10px] text-base-content/50">Total: <span class="font-bold">{{ $cutsThisMonth }}</span></div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-3">
                    <div class="badge badge-error badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Top Barbero</p>
                        <p class="text-sm font-black truncate">{{ $topStaff->name ?? '—' }}</p>
                        <p class="text-[9px] text-base-content/40 uppercase font-bold tracking-tighter">Más cortes este mes</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-3">
            <div class="xl:col-span-2 card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="card-title text-sm">
                        <h2>Cortes por Mes</h2>
                        <p class="text-xs text-base-content/50 font-normal normal-case">Últimos 6 meses</p>
                    </div>
                    <div class="relative h-[250px]" wire:ignore>
                        <canvas id="staffChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title text-sm">Comparativa Mensual</h2>
                    @php
                        $comparisons = [
                            ['label' => 'Cortes Realizados', 'current' => $cutsThisMonth, 'prev' => $cutsLastMonth, 'color' => 'warning'],
                            ['label' => 'Barberos Activos', 'current' => $activeThisMonth, 'prev' => $activeLastMonth, 'color' => 'secondary'],
                        ];
                    @endphp
                    @foreach($comparisons as $comp)
                        @php
                            $max = max($comp['current'], $comp['prev'], 1);
                            $pct = round(($comp['current'] / $max) * 100);
                            $diff = $comp['current'] - $comp['prev'];
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-medium text-base-content/60">{{ $comp['label'] }}</span>
                                <span class="text-xs font-bold {{ $diff >= 0 ? 'text-success' : 'text-error' }}">{{ $diff >= 0 ? '+' : '' }}{{ $diff }}</span>
                            </div>
                            <progress class="progress progress-{{ $comp['color'] }}" value="{{ $pct }}" max="100"></progress>
                            <div class="flex justify-between text-[10px] text-base-content/40 mt-1">
                                <span>Este mes: <strong class="text-base-content">{{ $comp['current'] }}</strong></span>
                                <span>Anterior: <strong class="text-base-content">{{ $comp['prev'] }}</strong></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 overflow-hidden">
            <div class="card-body p-4 pb-0">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider">Equipo de Barberos</h2>
                    <span class="badge badge-ghost badge-sm">{{ $totalStaff }} registrados</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-left">Barbero</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-left">Contacto</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">Rol</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">Cortes (Total)</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">Este Mes</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">Tendencia</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $member)
                            @php $trend = $member->cuts_this_month - $member->cuts_last_month; @endphp
                            <tr class="hover:bg-base-200/50">
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($member->avatar)
                                            <div class="avatar"><div class="w-8 rounded-full"><img src="/media/{{ $member->avatar }}" alt=""></div></div>
                                        @else
                                            <div class="avatar placeholder"><div class="w-8 rounded-full bg-primary/10 text-primary text-xs font-bold"><span>{{ strtoupper(substr($member->name, 0, 1)) }}</span></div></div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-sm">{{ $member->name }}</p>
                                            <p class="text-[10px] text-base-content/50 truncate max-w-[120px]">{{ $member->notes ?? 'Sin notas' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-xs"><p class="font-semibold text-base-content/60">{{ $member->email ?? '—' }}</p><p class="text-[10px]">{{ $member->phone ?? '—' }}</p></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-sm {{ $member->role === 'Admin' ? 'badge-secondary' : 'badge-ghost' }}">{{ $member->role ?? 'Staff' }}</span>
                                </td>
                                <td class="text-center text-sm font-black">{{ $member->appointments_count }}</td>
                                <td class="text-center text-sm font-black text-primary">{{ $member->cuts_this_month }}</td>
                                <td class="text-center">
                                    @if($trend > 0)
                                        <span class="badge badge-success badge-sm">▲ +{{ $trend }}</span>
                                    @elseif($trend < 0)
                                        <span class="badge badge-error badge-sm">▼ {{ $trend }}</span>
                                    @else
                                        <span class="badge badge-ghost badge-sm">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-1">
                                        <button wire:click="openEdit({{ $member->id }})" class="btn btn-xs btn-ghost btn-circle text-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" /></svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $member->id }})" class="btn btn-xs btn-ghost btn-circle text-error">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-base-content/50 font-bold uppercase tracking-wider text-xs py-8">No hay barberos registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-data="{ show: $wire.entangle('showModal') }" x-show="show" style="display:none"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>
            <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg bg-base-100 rounded-2xl shadow-2xl z-10 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="card-body p-5 border-b border-base-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black">{{ $isEditing ? 'Editar Barbero' : 'Nuevo Barbero' }}</h3>
                            <p class="text-[10px] text-base-content/50 font-bold uppercase tracking-wider mt-1">Completa los datos del equipo</p>
                        </div>
                        <button type="button" @click="show = false" class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </div>
                </div>
                <div class="p-5 overflow-y-auto custom-scrollbar">
                    <form wire:submit.prevent="save" class="space-y-4">
                        <div class="flex justify-center mb-4">
                            <div class="relative group">
                                <label class="cursor-pointer block">
                                    <div class="w-24 h-24 rounded-full border-4 border-base-300 shadow-md overflow-hidden bg-base-200 flex items-center justify-center">
                                        @if($avatar)
                                            <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @elseif($avatarPreview)
                                            <img src="/media/{{ $avatarPreview }}" class="w-full h-full object-cover">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                                        @endif
                                    </div>
                                    <input type="file" wire:model="avatar" class="hidden">
                                </label>
                                <div class="absolute -bottom-1 -right-1 bg-primary text-primary-content p-1.5 rounded-full shadow-lg border-2 border-base-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" /><circle cx="12" cy="13" r="4" /></svg>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Nombre Completo</span></label>
                                <input type="text" wire:model="name" class="input input-bordered">
                                @error('name') <span class="text-error text-xs font-bold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Email</span></label>
                                    <input type="email" wire:model="email" class="input input-bordered">
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Teléfono</span></label>
                                    <input type="text" wire:model="phone" class="input input-bordered">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Rol</span></label>
                                    <select wire:model="role" class="select select-bordered">
                                        <option value="Staff">Personal</option>
                                        <option value="Manager">Gerente</option>
                                        <option value="Admin">Administrador</option>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Notas</span></label>
                                    <input type="text" wire:model="notes" class="input input-bordered">
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="show = false" class="flex-1 btn btn-ghost">Cancelar</button>
                            <button type="submit" class="flex-1 btn btn-primary">
                                <span wire:loading.remove>Guardar Barbero</span>
                                <span wire:loading>Procesando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-data="{ show: $wire.entangle('staffToDelete') }" x-show="show" style="display:none"
            class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>
            <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-sm bg-base-100 rounded-2xl shadow-2xl z-10 text-center p-6">
                <div class="alert alert-error justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /></svg>
                </div>
                <h3 class="text-lg font-black mb-1.5">¿Eliminar barbero?</h3>
                <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider leading-relaxed">Esta acción borrará al miembro y su historial.</p>
                <div class="flex gap-3 mt-6">
                    <button @click="show = false" class="flex-1 btn btn-ghost">Cancelar</button>
                    <button wire:click="delete" class="flex-1 btn btn-error">Eliminar Ahora</button>
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
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e1b4b', padding: 10, cornerRadius: 8 } },
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