<div class="flex flex-col h-full min-h-0">
    <div class="space-y-4 overflow-y-auto custom-scrollbar flex-1 min-h-0 pr-1">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold">Catálogo de Servicios</h1>
                <p class="text-xs text-base-content/50 mt-0.5">Define los servicios y precios de tu barbería</p>
            </div>
            <button wire:click="openCreate" class="btn btn-primary btn-sm gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Nuevo Servicio
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-4">
                    <div class="badge badge-primary badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z" />
                            <line x1="16" y1="8" x2="2" y2="22" />
                            <line x1="17.5" y1="15" x2="9" y2="15" />
                        </svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Servicios
                            Ofrecidos</p>
                        <p class="text-xl font-black">{{ $totalServices }}</p>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4 flex-row items-center gap-4">
                    <div class="badge badge-success badge-lg opacity-90"><svg xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg></div>
                    <div>
                        <p class="text-[10px] font-bold text-base-content/50 uppercase tracking-wider">Precio Promedio
                        </p>
                        <p class="text-xl font-black">${{ number_format($averagePrice, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-left">
                                Nombre del Servicio</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">
                                Duración</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">
                                Precio</th>
                            <th class="text-[10px] uppercase tracking-wider text-base-content/50 font-bold text-center">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr class="hover:bg-base-200/50">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-base-200 flex items-center justify-center border border-base-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                            </svg>
                                        </div>
                                        <p class="font-bold text-sm">{{ $service->name }}</p>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-ghost badge-sm gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                        {{ $service->duration ?? 30 }} min
                                    </span>
                                </td>
                                <td class="text-center text-base font-black text-primary">
                                    ${{ number_format($service->price, 2) }}</td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-1">
                                        <button wire:click="openEdit({{ $service->id }})"
                                            class="btn btn-xs btn-ghost btn-circle text-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $service->id }})"
                                            class="btn btn-xs btn-ghost btn-circle text-error">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="text-center text-base-content/50 font-bold uppercase tracking-wider text-xs py-8">
                                    No hay servicios definidos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($services->hasPages())
                <div class="card-body p-4 pt-0">{{ $services->links() }}</div>
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
                class="relative w-full max-w-md bg-base-100 rounded-2xl shadow-2xl z-10 overflow-hidden">
                <div class="card-body p-5 border-b border-base-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black">{{ $isEditing ? 'Editar Servicio' : 'Nuevo Servicio' }}</h3>
                            <p class="text-[10px] text-base-content/50 font-bold uppercase tracking-wider mt-1">
                                Configura el servicio</p>
                        </div>
                        <button type="button" @click="show = false" class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </div>
                </div>
                <div class="p-5">
                    <form wire:submit.prevent="save" class="space-y-3">
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Nombre
                                    del Servicio</span></label>
                            <input type="text" wire:model="name" placeholder="Ej. Corte de Cabello + Barba"
                                class="input input-bordered">
                            @error('name') <span class="text-error text-xs font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="form-control">
                                <label class="label"><span
                                        class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Precio
                                        ($)</span></label>
                                <input type="number" step="0.01" wire:model="price" class="input input-bordered">
                                @error('price') <span class="text-error text-xs font-bold mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-control">
                                <label class="label"><span
                                        class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Duración
                                        (min)</span></label>
                                <input type="number" wire:model="duration" class="input input-bordered">
                            </div>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="show = false" class="flex-1 btn btn-ghost">Cancelar</button>
                            <button type="submit" class="flex-1 btn btn-primary">Guardar Servicio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-data="{ show: $wire.entangle('serviceToDelete') }" x-show="show" style="display:none"
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
                <h3 class="text-xl font-black mb-2">¿Eliminar servicio?</h3>
                <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider leading-relaxed">Esta acción
                    no se puede deshacer y afectará a los nuevos turnos.</p>
                <div class="flex gap-3 mt-8">
                    <button @click="show = false" class="flex-1 btn btn-ghost">Cancelar</button>
                    <button wire:click="delete" class="flex-1 btn btn-error">Eliminar</button>
                </div>
            </div>
        </div>

    </div>
</div>