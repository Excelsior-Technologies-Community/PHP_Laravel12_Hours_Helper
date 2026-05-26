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
        $selectedDate = $request->get(
            'date',
            Carbon::today()->format('Y-m-d')
        );

        // Search
        $search = $request->search;

        // Booked Slots
        $bookedSlots = Appointment::whereDate(
            'appointment_date',
            $selectedDate
        )
        ->pluck('appointment_time')
        ->map(fn($time) => date('H:i', strtotime($time)))
        ->toArray();

        // Generate Slots
        $morningSlots = HoursHelper::create('09:00', '12:00', 20);

        $eveningSlots = HoursHelper::create('16:00', '21:00', 30);

        // Appointment List
        $appointments = Appointment::when($search, function ($query) use ($search) {
                $query->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->oldest()
            ->paginate(3);

        return view('booking', compact(
            'morningSlots',
            'eveningSlots',
            'bookedSlots',
            'selectedDate',
            'appointments'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name'     => 'required|string|max:255',
            'email'            => 'required|email',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        // Prevent Duplicate Booking
        $exists = Appointment::where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This slot is already booked!');
        }

        Appointment::create($request->all());

        return back()->with('success', 'Appointment booked successfully!');
    }

    // Delete Appointment
    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();

        return back()->with('success', 'Appointment deleted successfully!');
    }
}