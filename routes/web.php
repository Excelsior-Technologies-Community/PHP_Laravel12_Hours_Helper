<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return redirect()->route('appointments.index');
});

Route::get('/book-appointment', [AppointmentController::class, 'index'])
    ->name('appointments.index');

Route::post('/book-appointment', [AppointmentController::class, 'store'])
    ->name('appointments.store');

Route::delete('/appointment/{id}', [AppointmentController::class, 'destroy'])
    ->name('appointments.destroy');