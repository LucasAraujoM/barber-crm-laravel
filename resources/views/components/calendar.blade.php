@php
    // Mock data mimicking what window.api.getNextTurns() would return
    // In a real app, this would come from a controller or database query
    if (!empty($appointments)) {
        $events = $appointments;
    } else {
        $events = [
            [
                'title' => 'Corte - Juan Pérez',
                'start' => now()->setTime(9, 0)->toIso8601String(),
                'end' => now()->setTime(10, 0)->toIso8601String(),
                'description' => 'Corte de pelo degradado',
                'status' => 'pendiente',
                'color' => '#3b82f6', // blue-500
            ],
            [
                'title' => 'Barba - David',
                'start' => now()->setTime(10, 30)->toIso8601String(),
                'end' => now()->setTime(11, 0)->toIso8601String(),
                'description' => 'Perfilado de barba',
                'status' => 'completado',
                'color' => '#22c55e', // green-500
            ],
            [
                'title' => 'Corte + Barba - Mike',
                'start' => now()->setTime(14, 0)->toIso8601String(),
                'end' => now()->setTime(15, 0)->toIso8601String(),
                'description' => 'Servicio completo',
                'status' => 'cancelado',
                'color' => '#ef4444', // red-500
            ]
        ];
    }
@endphp

<div class="bg-white p-5 rounded-lg shadow h-full">
    {{-- FullCalendar Container --}}
    <div id="calendar"></div>
</div>

{{-- Load FullCalendar from CDN --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridDay',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridDay,timeGridWeek,dayGridMonth'
            },
            locale: 'es',
            editable: true,
            height: 600,
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Agenda',
            },
            firstDay: 1,
            timeZone: 'America/Argentina/Buenos_Aires',
            dayMaxEvents: true,
            allDaySlot: false,
            scrollTime: '{{ now("America/Argentina/Buenos_Aires")->subHours(4)->format("H:i:s") }}',
            nowIndicator: true,
            events: @json($events),
            eventContent: function (info) {
                // Custom render for event content
                let title = document.createElement('div');
                title.className = 'fc-event-title font-bold';
                title.innerText = info.event.title;

                let desc = document.createElement('div');
                desc.className = 'fc-event-description text-xs opacity-90';
                desc.innerText = info.event.extendedProps.description;

                let container = document.createElement('div');
                container.className = 'flex flex-col p-1';
                container.appendChild(title);
                container.appendChild(desc);

                return { domNodes: [container] };
            }
        });
        calendar.render();
    });
</script>