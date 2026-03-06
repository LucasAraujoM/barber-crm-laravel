<div>
    <div class="space-y-4">

        {{-- ════ HEADER ════ --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-[#1F2937]">Catálogo de Servicios</h1>
                <p class="text-xs text-gray-500 mt-0.5">Define los servicios y precios de tu barbería</p>
            </div>
            <button wire:click="openCreate"
                class="inline-flex items-center px-4 py-2 bg-[#3B82F6] border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-[#2563EB] shadow-md cursor-pointer transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Servicio
            </button>
        </div>

        {{-- ════ MÉTRICAS ════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-[#3B82F6]/10 text-[#3B82F6] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z" />
                        <line x1="16" y1="8" x2="2" y2="22" />
                        <line x1="17.5" y1="15" x2="9" y2="15" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Servicios
                        Ofrecidos</p>
                    <p class="text-xl font-black text-[#1F2937] leading-none">{{ $totalServices }}</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-[#E5E7EB] flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Precio Promedio
                    </p>
                    <p class="text-xl font-black text-[#1F2937] leading-none">${{ number_format($averagePrice, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- ════ LISTADO ════ --}}
        <div class="bg-white rounded-[1.25rem] shadow-sm border border-[#E5E7EB] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-white text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            <th class="px-4 py-3 text-left">Nombre del Servicio</th>
                            <th class="px-4 py-3 text-center">Duración</th>
                            <th class="px-4 py-3 text-center">Precio</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($services as $service)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-white text-gray-500 flex items-center justify-center border border-[#E5E7EB]">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                            </svg>
                                        </div>
                                        <p class="font-bold text-[#1F2937] text-sm">{{ $service->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#F5F7FA] text-gray-500 text-xs font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                        {{ $service->duration ?? 30 }} min
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="text-base font-black text-[#3B82F6]">${{ number_format($service->price, 2) }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-1.5">
                                        <button wire:click="openEdit({{ $service->id }})"
                                            class="p-1.5 bg-white text-gray-500 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $service->id }})"
                                            class="p-1.5 bg-white text-gray-500 rounded-lg hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
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
                                    class="px-4 py-8 text-center text-gray-500 font-bold uppercase tracking-widest text-xs">
                                    No hay servicios definidos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($services->hasPages())
                <div class="px-4 py-3 border-t border-[#E5E7EB]">
                    {{ $services->links() }}
                </div>
            @endif
        </div>

        {{-- ════ MODAL SERVICIO ════ --}}
        <div x-data="{ show: $wire.entangle('showModal') }" x-show="show" style="display:none"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="show" x-transition.opacity class="absolute inset-0 bg-[#F5F7FA] backdrop-blur-sm"
                @click="show = false"></div>
            <div x-show="show" x-transition.scale
                class="relative w-full max-w-md bg-white rounded-[1.25rem] shadow-2xl z-10 overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-lg font-black text-[#1F2937] tracking-tight">
                                {{ $isEditing ? 'Editar Servicio' : 'Nuevo Servicio' }}
                            </h3>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">Configura
                                el
                                servicio</p>
                        </div>
                        <button @click="show = false" class="text-gray-500 hover:text-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path d="M18 6L6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div class="space-y-3">
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Nombre
                                    del Servicio</label>
                                <input type="text" wire:model="name" placeholder="Ej. Corte de Cabello + Barba"
                                    class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                @error('name') <span
                                class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Precio
                                        ($)</label>
                                    <input type="number" step="0.01" wire:model="price"
                                        class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                    @error('price') <span
                                    class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Duración
                                        (min)</label>
                                    <input type="number" wire:model="duration"
                                        class="w-full bg-white border border-[#E5E7EB] rounded-xl py-3 px-4 text-sm font-bold text-[#1F2937] outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="show = false"
                                class="flex-1 bg-white border-2 border-[#E5E7EB] text-gray-500 font-black py-4 rounded-2xl hover:bg-gray-50 transition-all text-xs uppercase tracking-widest">Cancelar</button>
                            <button type="submit"
                                class="flex-1 bg-[#3B82F6] hover:bg-[#2563EB] text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 text-xs uppercase tracking-widest">
                                Guardar Servicio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ════ MODAL ELIMINAR ════ --}}
        <div x-data="{ toDelete: $wire.entangle('serviceToDelete') }" x-show="toDelete" style="display:none"
            class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div x-show="toDelete" x-transition.opacity class="absolute inset-0 bg-[#1E293B]/60 backdrop-blur-sm"
                @click="toDelete = null"></div>
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
                <h3 class="text-xl font-black text-[#1F2937] mb-2">¿Eliminar servicio?</h3>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest leading-relaxed">Esta acción no
                    se puede deshacer y afectará a los nuevos turnos.</p>
                <div class="flex gap-3 mt-8">
                    <button @click="toDelete = null"
                        class="flex-1 bg-white text-gray-500 font-black py-3.5 rounded-xl hover:bg-gray-50 transition-all text-[10px] uppercase tracking-widest">Cancelar</button>
                    <button wire:click="delete"
                        class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-black py-3.5 rounded-xl transition-all shadow-lg shadow-rose-200 text-[10px] uppercase tracking-widest">Eliminar</button>
                </div>
            </div>
        </div>

    </div>
</div>