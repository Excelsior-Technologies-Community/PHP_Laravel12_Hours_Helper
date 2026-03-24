<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Label84\HoursHelper\Facades\HoursHelper;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // Get selected date or default to today
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));

        // Fetch already booked slots for the SELECTED date
        $bookedSlots = Appointment::whereDate('appointment_date', $selectedDate)
            ->pluck('appointment_time')
            ->map(fn($time) => date('H:i', strtotime($time)))
            ->toArray();

        // Generate Slots
        $morningSlots = HoursHelper::create('09:00', '12:00', 20);
        $eveningSlots = HoursHelper::create('16:00', '21:00', 30);

        return view('booking', compact('morningSlots', 'eveningSlots', 'bookedSlots', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'email' => 'required|email',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        Appointment::create($request->all());

        return back()->with('success', 'Your appointment has been successfully scheduled!');
    }
}