<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', [Controller::class, 'index'])->name('home');
Route::get('/staff', [Controller::class, 'staff'])->name('staff');
Route::post('/staff/store', [Controller::class, 'store'])->name('staff.store');
Route::put('/staff/update/{id}', [Controller::class, 'update'])->name('staff.update');
Route::get('/staff/destroy/{id}', [Controller::class, 'destroy'])->name('staff.destroy');
