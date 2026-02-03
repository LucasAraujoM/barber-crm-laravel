@php
    $turns = [
        ['time' => "09:00", 'barber' => "Chris", 'client' => "Juan Pérez"],
        ['time' => "10:00", 'barber' => "David", 'client' => "Ana Torres"],
        ['time' => "11:00", 'barber' => "Mike", 'client' => "Luis Martínez"],
    ];
@endphp

<div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
    <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Agenda de Turnos</h2>
        <span class="text-sm font-medium text-blue-600 px-3 py-1 bg-blue-50 rounded-full">Hoy</span>
    </div>

    <div class="p-4">
        <div class="space-y-3">
            @foreach ($turns as $turn)
                <div class="group flex items-center gap-4">
                    <span class="w-14 text-sm font-medium text-gray-400 group-hover:text-blue-600 transition-colors">
                        {{ $turn['time'] }}
                    </span>
                    <div
                        class="flex-1 bg-white border border-gray-100 rounded-lg p-3 shadow-sm hover:shadow-md hover:border-blue-200 border-l-4 border-l-blue-500 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-900">{{ $turn['client'] }}</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                    Barbero: {{ $turn['barber'] }}
                                </p>
                            </div>
                            <button class="text-xs font-semibold text-blue-600 hover:underline">Ver detalle</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>