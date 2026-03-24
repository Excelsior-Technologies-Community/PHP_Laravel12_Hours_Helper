<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/book-appointment', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/book-appointment', [AppointmentController::class, 'store'])->name('appointments.store');

Route::get('/', function () {
    return redirect()->route('appointments.index');
});