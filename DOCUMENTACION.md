# Documentación Técnica - Barber CRM

Este documento detalla el funcionamiento interno de la aplicación Barber CRM, su arquitectura de archivos locales y el propósito de cada una de sus funciones principales. La aplicación está diseñada para funcionar **100% offline** y de forma nativa.

## 1. Arquitectura de Archivos Locales (Offline)

Para eliminar cualquier dependencia de internet (CDNs), la aplicación utiliza **Vite** como empaquetador de activos. Todas las librerías externas han sido instaladas vía `npm` y se incluyen en el bundle final.

### Librerías Integradas:
- **Alpine.js**: Utilizado para la interactividad de la UI (modales, notificaciones, formularios dinámicos).
- **Chart.js**: Utilizado para generar los gráficos de estadísticas en el Dashboard y secciones de Barberos/Clientes.
- **FullCalendar**: Sistema de calendario para la gestión de turnos.
- **FilePond**: Componente para la subida de imágenes de perfil (avatares) de barberos.
- **Tipografía**: Utiliza un "System Font Stack" (fuentes nativas del sistema) para evitar descargas de Google Fonts.

### Configuración de Activos:
- `resources/js/app.js`: Importa e inicializa todas las librerías mencionadas, exponiéndolas al objeto global `window`.
- `resources/css/app.css`: Gestiona los estilos de Tailwind CSS y los estilos específicos de FilePond.
- `vite.config.js`: Configura la compilación de estos archivos para que NativePHP los sirva localmente.

---

## 2. Documentación de Controladores

### `Controller.php` (Dashboard Principal)
Controla la página de inicio (`/`).
- `index()`: 
  - Calcula métricas rápidas (turnos de hoy, turno siguiente, clientes nuevos).
  - Prepara los datos para el calendario de FullCalendar.
  - Formatea la actividad reciente y el gráfico semanal de productividad.
  - Carga listas de clientes, barberos y servicios para los accesos rápidos.

### `AppointmentController.php` (Gestión de Turnos)
- `index(Request $request)`: Filtra y lista el historial de turnos. Incluye lógica de paginación y filtrado por fecha o barbero.
- `store(Request $request)`: Crea un nuevo turno. Maneja tanto clientes registrados como invitados y sincroniza los servicios seleccionados.
- `update(Request $request, $id)`: Permite editar los detalles de un turno existente o actualizar su estado (pendiente, completado, etc.).
- `destroy($id)`: Elimina permanentemente un turno.
- `checkAvailability(Request $request)`: **(Internal API)** Verifica en tiempo real si un barbero está libre en un horario específico antes de asignar el turno.

### `StaffController.php` (Gestión de Barberos)
- `index()`: Lista al equipo y calcula estadísticas de rendimiento individual (cortes realizados, tendencia vs mes anterior).
- `store(Request $request)`: Registra un nuevo barbero. Incluye la lógica para guardar el avatar en el almacenamiento local (`app-data/images`).
- `update(Request $request, $id)`: Actualiza la información del barbero y gestiona el reemplazo de imágenes de perfil.
- `destroy($id)`: Elimina un barbero y su foto asociada.

### `ClientController.php` (Gestión de Clientes)
- `index(Request $request)`: Muestra la base de datos de clientes con buscadores por nombre, email o teléfono.
- Calcula el "valor" del cliente basado en su historial de turnos y frecuencia.

### `ServiceController.php` (Gestión de servicios)
- `index()`: Lista los servicios ofrecidos y su precio promedio.
- `store()` / `update()` / `destroy()`: Operaciones estándar de CRUD para el catálogo de la barbería.

---

## 3. Lógica de Interfaz (Alpine.js)

La aplicación utiliza componentes Alpine.js incrustados en las vistas para mejorar la experiencia de usuario sin recargar la página:

- **Modales dinámicos**: Se abren y cierran mediante estados de Alpine (`showModal`).
- **Carga de datos al editar**: Al hacer clic en editar, la función JS `openEdit(item)` puebla el formulario reactivamente.
- **Sistema de Toast**: La función `window.toast(mensaje, tipo)` permite mostrar alertas elegantes que desaparecen automáticamente.
- **Verificación de disponibilidad**: En el formulario de turnos, se vigilan los cambios de fecha/hora para llamar a `checkAvailability` automáticamente.

---

## 4. Notas de Desarrollo

- **Almacenamiento**: Las imágenes se guardan fuera de la carpeta pública (`app-data/`) para persistir entre instalaciones, según el estándar de NativePHP.
- **Base de Datos**: Utiliza SQLite local, lo que garantiza que los datos siempre residan en el equipo del usuario.
- **Instalación Offline**: Una vez que se ejecuta `npm run build`, la carpeta `public/build` contiene todo lo necesario para que la aplicación funcione sin internet para siempre.
