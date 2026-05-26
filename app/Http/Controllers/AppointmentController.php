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

        $search = $request->search;

        $bookedSlots = Appointment::whereDate('appointment_date', $selectedDate)
            ->pluck('appointment_time')
            ->map(fn($time) => date('H:i', strtotime($time)))
            ->toArray();

        $morningSlots = HoursHelper::create('09:00', '12:00', 20);

        $eveningSlots = HoursHelper::create('16:00', '21:00', 30);

        $appointments = Appointment::when($search, function ($query) use ($search) {
            $query->where('patient_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(3);

        $totalAppointments = Appointment::count();

        $todayAppointments = Appointment::whereDate(
            'appointment_date',
            today()
        )->count();

        $pendingAppointments = Appointment::where(
            'status',
            'Pending'
        )->count();

        $confirmedAppointments = Appointment::where(
            'status',
            'Confirmed'
        )->count();

        return view('booking', compact(
            'morningSlots',
            'eveningSlots',
            'bookedSlots',
            'selectedDate',
            'appointments',
            'totalAppointments',
            'todayAppointments',
            'pendingAppointments',
            'confirmedAppointments'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'email' => 'required|email',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $exists = Appointment::where(
            'appointment_date',
            $request->appointment_date
        )
            ->where(
                'appointment_time',
                $request->appointment_time
            )
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'Slot already booked'
            );
        }

        Appointment::create([
            'patient_name' => $request->patient_name,
            'email' => $request->email,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'Pending'
        ]);

        return back()->with(
            'success',
            'Appointment booked successfully'
        );
    }

    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Appointment deleted'
        );
    }

    public function updateStatus(Request $request, $id)
    {
        Appointment::findOrFail($id)->update([
            'status' => $request->status
        ]);

        return back()->with(
            'success',
            'Status updated'
        );
    }
}
