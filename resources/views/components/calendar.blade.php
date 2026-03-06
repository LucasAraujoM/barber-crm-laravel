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

<div class="w-full relative" x-data="{
    events: @js($events),
    calendar: null,
    initCalendar() {
        const el = this.$refs.calendarEl;
        if (!el || !window.FullCalendar) {
            setTimeout(() => this.initCalendar(), 100);
            return;
        }

        if (this.calendar) this.calendar.destroy();

        this.calendar = new window.FullCalendar.Calendar(el, {
            plugins: [window.FullCalendar.dayGridPlugin, window.FullCalendar.timeGridPlugin, window.FullCalendar.interactionPlugin],
            initialView: 'timeGridDay',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridDay,timeGridWeek'
            },
            locale: 'es',
            editable: true,
            height: 693, // Very compact height for dashboard
            slotMinTime: '08:00:00', //agregar start_time
            slotMaxTime: '22:00:00', //agregar end_time
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
            scrollTime: new Date().getHours() + ':00:00',
            selectable: true,
            nowIndicator: true,
            events: this.events,
            dateClick: (info) => {
                const date = info.dateStr.split('T')[0];
                const time = info.dateStr.split('T')[1] ? info.dateStr.split('T')[1].substring(0, 5) : '08:00';
                $wire.openCreateFromCalendar(date, time);
            },
            eventClick: (info) => {
                $wire.editAppointment(info.event.id);
            },
            eventDrop: (info) => {
                // When an event is dragged and dropped
                const newStart = info.event.start;
                const newEnd = info.event.end;
                $wire.moveAppointment(
                    info.event.id, 
                    newStart ? newStart.toISOString() : null, 
                    newEnd ? newEnd.toISOString() : null
                );
            },
            eventResize: (info) => {
                // When an event is resized
                const newStart = info.event.start;
                const newEnd = info.event.end;
                $wire.moveAppointment(
                    info.event.id, 
                    newStart ? newStart.toISOString() : null, 
                    newEnd ? newEnd.toISOString() : null
                );
            },
            eventContent: function (info) {
                let title = document.createElement('div');
                title.className = 'fc-event-title font-bold text-[10px] uppercase truncate leading-tight';
                title.innerText = info.event.title;

                let desc = document.createElement('div');
                desc.className = 'fc-event-description text-[9px] opacity-80 truncate';
                desc.innerText = info.event.extendedProps.description || info.event.extendedProps.status;

                let container = document.createElement('div');
                container.className = 'flex flex-col p-1 overflow-hidden';
                container.appendChild(title);
                if (info.event.extendedProps.description || info.event.extendedProps.status) {
                    container.appendChild(desc);
                }

                return { domNodes: [container] };
            }
        });

        // Render carefully to avoid blank screen bugs
        setTimeout(() => {
            this.calendar.render();
        }, 50);
    }
}" x-init="initCalendar()">
    {{-- FullCalendar Container --}}
    <div x-ref="calendarEl" class="w-full min-h-[380px]"></div>
</div>

<style>
    .fc {
        --fc-border-color: #E5E7EB;
        --fc-button-bg-color: #ffffff;
        --fc-button-border-color: #E5E7EB;
        --fc-button-text-color: #1F2937;
        --fc-button-hover-bg-color: #F3F4F6;
        --fc-button-hover-border-color: #D1D5DB;
        --fc-button-active-bg-color: #3B82F6;
        --fc-button-active-border-color: #3B82F6;
        font-family: inherit;
    }

    .fc .fc-toolbar-title {
        font-size: 1.1rem;
        font-weight: 900;
        color: #1F2937;
        text-transform: capitalize;
    }

    .fc .fc-button {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 0.75rem;
        text-transform: uppercase;
        transition: all 0.2s;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #3B82F6 !important;
        border-color: #3B82F6 !important;
        color: white !important;
    }

    .fc .fc-button-primary:focus {
        box-shadow: none !important;
    }

    .fc-v-event {
        border: none !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .fc-timegrid-slot {
        height: 2rem !important;
    }

    /* A bit smaller row height for compact view */
    .fc-col-header-cell {
        padding: 0.5rem 0 !important;
        background: #F5F7FA;
        border: none !important;
    }

    .fc-col-header-cell-cushion {
        font-size: 0.7rem;
        font-weight: 800;
        color: #9ca3af;
        text-transform: uppercase;
        text-decoration: none !important;
    }

    .fc-timegrid-axis-cushion,
    .fc-timegrid-slot-label-cushion {
        font-size: 0.65rem;
        font-weight: 700;
        color: #9ca3af;
    }
</style>