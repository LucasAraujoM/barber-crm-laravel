@php
    // Mock data mimicking what window.api.getNextTurns() would return
    // In a real app, this would come from a controller or database query
    if (!empty($appointments)) {
        $events = $appointments;
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
            height: 750, // Very compact height for dashboard
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
                $wire.moveAppointment(
                    info.event.id, 
                    info.event.startStr, 
                    info.event.endStr
                );
            },
            eventResize: (info) => {
                // When an event is resized
                const newStart = info.event.start;
                const newEnd = info.event.end;
                $wire.moveAppointment(
                    info.event.id, 
                    info.event.startStr, 
                    info.event.endStr
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
    },
    async refreshCalendar() {
        if (!this.calendar) return;
        const freshEvents = await $wire.getEvents();
        this.calendar.removeAllEvents();
        this.calendar.addEventSource(freshEvents);
    }
}" x-init="initCalendar()" @calendar-updated.window="refreshCalendar()">
    {{-- FullCalendar Container --}}
    <div x-ref="calendarEl" class="w-full min-h-[380px]"></div>
</div>

{{-- Los estilos de FullCalendar se cargan desde resources/css/app.css con soporte dark/light --}}