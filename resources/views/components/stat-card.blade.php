@props(['title', 'value'])

<div class="bg-white shadow rounded p-4 flex flex-col">
    <span class="text-gray-500 text-sm">{{ $title }}</span>
    <span class="text-2xl font-bold">{{ $value }}</span>
</div>