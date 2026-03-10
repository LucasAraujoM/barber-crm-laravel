<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\StaffManagement;
use App\Livewire\ClientManagement;
use App\Livewire\ServiceManagement;
use App\Livewire\AppointmentManagement;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', Dashboard::class)->name('home');

// Management Sections (Livewire)
Route::get('/turnos', AppointmentManagement::class)->name('appointments.index');
Route::get('/clientes', ClientManagement::class)->name('clients');
Route::get('/servicios', ServiceManagement::class)->name('services.index');
Route::get('/staff', StaffManagement::class)->name('staff');

// Legacy / Helper Routes
Route::get('/appointments/check-availability', [AppointmentController::class, 'checkAvailability'])->name('appointments.check-availability');

// Media Handler (Local Images)
Route::get('/media/{path}', function ($path) {
    $fullPath = base_path('app-data/images/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
});

Route::get('/events', function () {
    return Event::all()->map(function ($event) {
        return [
            'title' => $event->title,
            'start' => $event->start_date,
            'end' => $event->end_date,
        ];
    });
});