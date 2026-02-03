@php
    $clients = [
        ['name' => 'Mario Gomez', 'hour' => '10:00 AM'],
        ['name' => 'Lucia Fernandez', 'hour' => '11:30 AM'],
        ['name' => 'Carlos Ruiz', 'hour' => '02:15 PM'],
        ['name' => 'Ana Mendoza', 'hour' => '04:00 PM'],
    ];
@endphp

<div class="bg-white shadow rounded-lg p-5">
    <h2 class="font-bold text-lg mb-4 text-gray-800">Clientes Recientes</h2>

    <ul class="space-y-3">
        @foreach ($clients as $client)
            <li class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-gray-700 font-medium">{{ $client['name'] }}</span>
                <span class="text-sm text-gray-400 italic">{{ $client['hour'] }}</span>
            </li>
        @endforeach
    </ul>
</div>