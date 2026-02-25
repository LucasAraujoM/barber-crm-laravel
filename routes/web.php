<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [Controller::class, 'index'])->name('home');

// Staff
Route::get('/staff', [StaffController::class, 'index'])->name('staff');
Route::post('/staff/store', [StaffController::class, 'store'])->name('staff.store');
Route::put('/staff/update/{id}', [StaffController::class, 'update'])->name('staff.update');
Route::get('/staff/destroy/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

// Clientes
Route::get('/clientes', [ClientController::class, 'index'])->name('clients');

// Servicios
Route::resource('services', ServiceController::class)->except(['create', 'edit', 'show']);

// Turnos
Route::resource('appointments', AppointmentController::class)->except(['create', 'edit', 'show']);

// Media Handler
Route::get('/media/{path}', function ($path) {
    $fullPath = base_path('app-data/images/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*');