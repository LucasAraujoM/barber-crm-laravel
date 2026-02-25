# Barber CRM 💈

Sistema de gestión para barberías construido con Laravel y NativePHP.

## Características

- **Panel de Control**: Métricas en tiempo real de turnos, clientes y crecimiento.
- **Gestión de Turnos**: Calendario interactivo y agendamiento rápido.
- **Gestión de Clientes**: Historial completo y seguimiento de actividad.
- **Gestión de Barberos (Staff)**: Seguimiento de rendimiento y asignación de servicios.
- **Catálogo de Servicios**: Configuración de servicios y precios.

## Instalación

1. Clona el repositorio
2. Instala las dependencias: `composer install` y `npm install`
3. Copia el archivo `.env.example` a `.env`
4. Genera la clave de la aplicación: `php artisan key:generate`
5. Ejecuta las migraciones: `php artisan migrate`
6. Inicia el servidor de desarrollo: `npm run dev`

## Tecnologías Utilizadas

- **Laravel**: Framework PHP robusto.
- **NativePHP**: Para convertir la web en una aplicación de escritorio.
- **Tailwind CSS**: Estilizado moderno y responsivo.
- **Alpine.js**: Interactividad ligera en el frontend.
- **Chart.js**: Visualización de datos y métricas.
- **FullCalendar**: Agenda interactiva.

## Licencia

Este proyecto es software de código abierto bajo la licencia [MIT](https://opensource.org/licenses/MIT).
