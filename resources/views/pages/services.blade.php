@extends('layout.app')

@section('title', 'Servicios')

@section('content')
    <div class="space-y-6">

        {{-- HEADER & METRICS --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">Catálogo de Servicios</h1>
                <p class="text-sm text-gray-400 mt-1">Administra los servicios ofrecidos y asigna precios</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path d="M12 20V10" />
                            <path d="M18 20V4" />
                            <path d="M6 20v-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Promedio</p>
                        <p class="text-sm font-bold text-gray-800">${{ number_format($averagePrice, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path d="M3 3v18h18" />
                            <path d="m19 9-5 5-4-4-3 3" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Total</p>
                        <p class="text-sm font-bold text-gray-800">{{ $totalServices }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
            x-data="{ openModal: false, editing: false, form: { id: null, name: '', description: '', price: '', notes: '' } }">

            {{-- Toolbar --}}
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Listado de Servicios</h2>
                <button
                    @click="openModal = true; editing = false; form = { id: null, name: '', description: '', price: '', notes: '' }"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Nuevo Servicio
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 w-1/4">Nombre</th>
                            <th class="px-6 py-3">Descripción</th>
                            <th class="px-6 py-3 w-32 text-right">Precio</th>
                            <th class="px-6 py-3 text-center w-24">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $service->name }}</td>
                                <td class="px-6 py-4 text-gray-500 truncate max-w-xs">{{ $service->description ?? '—' }}</td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                    ${{ number_format($service->price, 1) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            @click="openModal = true; editing = true; form = { id: {{ $service->id }}, name: '{{ $service->name }}', description: '{{ $service->description }}', price: '{{ $service->price }}', notes: '{{ $service->notes }}' }"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este servicio?');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-gray-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18" />
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">No hay servicios registrados
                                    aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($services->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $services->links() }}
                </div>
            @endif

            {{-- MODAL (Alpine.js) --}}
            <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                @keydown.escape.window="openModal = false">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                    {{-- Backdrop --}}
                    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="openModal = false"
                        aria-hidden="true">
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    {{-- Modal Content --}}
                    <div x-show="openModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200">

                        <form :action="editing ? '{{ url('services') }}/' + form.id : '{{ route('services.store') }}'"
                            method="POST">
                            @csrf
                            <template x-if="editing">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-6 text-center"
                                    x-text="editing ? 'Editar Servicio' : 'Nuevo Servicio'"></h3>

                                <div class="space-y-4">
                                    <label for="name"
                                        class="block text-sm font-semibold text-gray-600 mb-1 text-center">Nombre del
                                        Servicio</label>
                                    <input type="text" name="name" id="name" x-model="form.name" required
                                        class="w-full px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 bg-gray-50/30 focus:bg-white transition-all"
                                        placeholder="Ej. Corte de Cabello">

                                    <div>
                                        <label for="description"
                                            class="block text-sm font-semibold text-gray-600 mb-1 text-center">Descripción
                                            (Opcional)</label>
                                        <textarea name="description" id="description" x-model="form.description" rows="2"
                                            class="w-full px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 bg-gray-50/30 focus:bg-white transition-all"
                                            placeholder="Breve descripción del servicio..."></textarea>
                                    </div>

                                    <div>
                                        <label for="price"
                                            class="block text-sm font-semibold text-gray-600 mb-1 text-center">Precio del
                                            Servicio</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                <span class="text-gray-400 sm:text-sm font-bold">$</span>
                                            </div>
                                            <input type="number" name="price" id="price" x-model="form.price" step="0.01"
                                                min="0" required
                                                class="w-full px-4 rounded-lg border-gray-300 pl-8 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 font-bold text-indigo-600 bg-gray-50/30 focus:bg-white transition-all">
                                        </div>
                                    </div>

                                    <div x-show="false">
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas
                                            Internas</label>
                                        <textarea name="notes" id="notes" x-model="form.notes" rows="2"
                                            class="w-full px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                    Guardar
                                </button>
                                <button type="button" @click="openModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection