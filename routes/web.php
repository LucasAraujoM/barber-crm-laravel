<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\StaffManagement;
use App\Livewire\ClientManagement;
use App\Livewire\ServiceManagement;
use App\Livewire\AppointmentManagement;
use App\Livewire\Settings;
use App\Http\Controllers\AppointmentController;
use App\Http\Middleware\CheckAppPassword;

Route::get('/unlock', function (\Illuminate\Http\Request $request) {
    $password = $request->query('p', '');
    if (empty($password)) {
        return response()->json(['error' => 'Password required'], 400);
    }
    $storedHash = \App\Models\Setting::get('app_password');
    if (empty($storedHash)) {
        return response()->json(['success' => true]);
    }
    if (\Illuminate\Support\Facades\Hash::check($password, $storedHash)) {
        $request->session()->put('app_unlocked', true);
        return response()->json(['success' => true]);
    }
    return response()->json(['error' => 'Invalid password'], 401);
})->name('unlock');

Route::middleware(CheckAppPassword::class)->group(function () {
    Route::get('/', Dashboard::class)->name('home');
    Route::get('/turnos', AppointmentManagement::class)->name('appointments.index');
    Route::get('/clientes', ClientManagement::class)->name('clients');
    Route::get('/servicios', ServiceManagement::class)->name('services.index');
    Route::get('/staff', StaffManagement::class)->name('staff');
    Route::get('/configuracion', Settings::class)->name('settings');
});

Route::get('/appointments/check-availability', [AppointmentController::class, 'checkAvailability'])->name('appointments.check-availability');

Route::get('/media/{path}', function ($path) {
    $fullPath = base_path('app-data/images/' . $path);
    if (!file_exists($fullPath)) { abort(404); }
    return response()->file($fullPath);
});