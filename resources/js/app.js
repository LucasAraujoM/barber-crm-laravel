import './bootstrap';

// Livewire 3 ya incluye e inicializa Alpine.js automáticamente.
// Evitamos inicializarlo por duplicado en Vite para no romper el binding $wire.
// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();

import Chart from 'chart.js/auto';
window.Chart = Chart;

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';

window.FullCalendar = {
    Calendar: Calendar,
    dayGridPlugin: dayGridPlugin,
    timeGridPlugin: timeGridPlugin,
    interactionPlugin: interactionPlugin,
    esLocale: esLocale
};

import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

window.FilePond = FilePond;
window.FilePondPluginImagePreview = FilePondPluginImagePreview;
