@extends('layout.app')

@section('title', 'Personal')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión del Personal</h1>
            <x-add-staff-modal />
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr
                        class="bg-gray-50 border-b border-gray-200 text-gray-600 text-left text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Correo</th>
                        <th class="px-6 py-3">Rol</th>
                        <th class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($staff as $member)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($member->avatar)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $member->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $member->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $member->role }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <div class="flex justify-center space-x-3">
                                    <x-edit-staff-modal :staff="$member" />
                                    <x-delete-staff-modal :staff="$member" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection