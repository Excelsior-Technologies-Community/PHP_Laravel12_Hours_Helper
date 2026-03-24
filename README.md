# PHP_Laravel12_Hours_Helper

## Project Description

**PHP_Laravel12_Hours_Helper** is a Laravel 12 application that demonstrates how to build a **Doctor Appointment Booking System** using the [laravel-hours-helper]package by **Label84**.

The `HoursHelper` package makes it easy to generate time slot ranges (e.g., every 20 minutes from 9:00 AM to 12:00 PM). Users can select a date, view available and booked time slots, and confirm an appointment by filling in their name and email.

This project is **beginner-friendly** and helps understand how to use third-party packages to generate dynamic time slots in a real-world booking system.

---

## Features

- 📅 Date selection using Flatpickr calendar (minimum date = today)
- ⏰ Dynamic time slot generation using `HoursHelper::create()`
- 🟢 Available and 🔴 Booked slots displayed visually
- 📋 Booking form (Name + Email) via Bootstrap Modal
- ✅ Server-side validation for all form fields
- 🔄 Booked slots update per selected date (date-wise filtering)
- 💬 Success message after booking confirmation
- 🌑 Clean and responsive UI using Bootstrap 5 + Inter Font

---

## Technologies Used

| Technology | Purpose |
|---|---|
| PHP 8.1+ | Backend Language |
| Laravel 12 | PHP Framework |
| MySQL | Database |
| laravel-hours-helper | Time slot generation package by Label84 |
| Carbon | Date and time manipulation |
| Bootstrap 5 | UI Styling and Modal |
| Flatpickr | Date Picker Calendar |
| Google Fonts (Inter) | Typography |

---

## How It Works

```
User selects date  →  HoursHelper generates slots  →  Booked slots filtered  →  User books slot  →  Saved to DB! 🎉
```

1. User visits `/book-appointment` and selects a date from the calendar.
2. `AppointmentController` fetches already booked slots for that date from the database.
3. `HoursHelper::create()` generates morning and evening time slots.
4. Booked slots are shown as disabled (grayed out); available slots are clickable.
5. User clicks a slot → Bootstrap Modal opens → User fills Name and Email → Submits.
6. Appointment is saved to the `appointments` table in MySQL.

---

## Installation Steps

---

### STEP 1: Create Laravel 12 Project

Open terminal / CMD and run:

```bash
composer create-project laravel/laravel PHP_Laravel12_Hours_Helper "12.*"
```

Go inside the project folder:

```bash
cd PHP_Laravel12_Hours_Helper
```

> This installs a fresh Laravel 12 project and moves into the project folder.

---

### STEP 2: Database Setup

Update `.env` with your database details:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=php_laravel12_hours_helper_db
DB_USERNAME=root
DB_PASSWORD=
```

Create database in MySQL / phpMyAdmin:

```
Database name: php_laravel12_hours_helper_db
```

Then run:

```bash
php artisan migrate
```

> Connects Laravel with MySQL and creates the default tables.

---

### STEP 3: Install the Hours Helper Package

```bash
composer require label84/laravel-hours-helper
```

> Installs the `laravel-hours-helper` package which provides the `HoursHelper` facade to generate time slot ranges easily.

---

### STEP 4: Create the Appointment Model and Migration

Run:

```bash
php artisan make:model Appointment -m
```

Open: `database/migrations/xxxx_create_appointments_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('email');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
```

Then run:

```bash
php artisan migrate
```

Open: `app/Models/Appointment.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'email',
        'appointment_date',
        'appointment_time',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
    ];
}
```

> Creates the `appointments` table and the `Appointment` model with fillable fields and casts.

---

### STEP 5: Create the Controller

Run:

```bash
php artisan make:controller AppointmentController
```

Open: `app/Http/Controllers/AppointmentController.php`

```php
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

        // Fetch already booked slots for the selected date
        $bookedSlots = Appointment::whereDate('appointment_date', $selectedDate)
            ->pluck('appointment_time')
            ->map(fn($time) => date('H:i', strtotime($time)))
            ->toArray();

        // Generate time slots using HoursHelper
        // Morning: 9:00 AM to 12:00 PM, every 20 minutes
        $morningSlots = HoursHelper::create('09:00', '12:00', 20);

        // Evening: 4:00 PM to 9:00 PM, every 30 minutes
        $eveningSlots = HoursHelper::create('16:00', '21:00', 30);

        return view('booking', compact('morningSlots', 'eveningSlots', 'bookedSlots', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name'     => 'required|string|max:255',
            'email'            => 'required|email',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        Appointment::create($request->all());

        return back()->with('success', 'Your appointment has been successfully scheduled!');
    }
}
```

> `index()` generates slots and filters booked ones per selected date.
> `store()` validates the form and saves the appointment to the database.

---

### STEP 6: Add Routes

Open: `routes/web.php`

```php
<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/book-appointment', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/book-appointment', [AppointmentController::class, 'store'])->name('appointments.store');

Route::get('/', function () {
    return redirect()->route('appointments.index');
});
```

> Defines two routes — GET for showing the booking page and POST for saving the appointment.
> The root `/` redirects directly to the booking page.

---

### STEP 7: Create the Blade View

Create file: `resources/views/booking.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Doctor Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        body { background-color: #f0f4f8; font-family: 'Inter', sans-serif; color: #334155; }
        .main-container { max-width: 1100px; margin: 50px auto; }
        .doc-sidebar { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .booking-content { background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .profile-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #e2e8f0; }
        .badge-specialty { background: #e0f2fe; color: #0369a1; padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .slot-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 12px; }
        .time-btn { border: 2px solid #e2e8f0; background: white; padding: 10px; border-radius: 12px; transition: all 0.2s; font-weight: 600; }
        .time-btn:hover:not(:disabled) { border-color: #0ea5e9; color: #0ea5e9; transform: translateY(-2px); }
        .time-btn.booked { background: #f1f5f9; border-color: #f1f5f9; color: #cbd5e1; cursor: not-allowed; }
        .date-picker-input { background: #f8fafc; border: 2px solid #e2e8f0; padding: 12px; border-radius: 12px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>

<div class="container main-container">
    <div class="row g-4">

        <!-- Doctor Sidebar -->
        <div class="col-lg-4">
            <div class="doc-sidebar text-center">
                <img src="https://img.freepik.com/free-photo/doctor-offering-medical-teleconsultation_23-2149329007.jpg" class="profile-img mb-3">
                <h4 class="fw-bold">Dr. Sameer Patel</h4>
                <span class="badge-specialty">Senior Cardiologist</span>
                <p class="text-muted mt-3 px-2">With over 15 years of experience in cardiovascular surgery and patient care.</p>
                <hr>
                <div class="d-flex justify-content-around mt-3 text-start">
                    <div><small class="text-muted d-block">Experience</small><strong>15+ Years</strong></div>
                    <div><small class="text-muted d-block">Patients</small><strong>2,000+</strong></div>
                    <div><small class="text-muted d-block">Rating</small><strong>⭐ 4.9</strong></div>
                </div>
            </div>
        </div>

        <!-- Booking Panel -->
        <div class="col-lg-8">
            <div class="booking-content">
                <h3 class="fw-bold mb-4">Book an Appointment</h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Step 1: Date -->
                <div class="mb-5">
                    <label class="form-label fw-bold mb-3">Step 1: Select Date</label>
                    <form id="dateForm" action="{{ route('appointments.index') }}" method="GET">
                        <input type="text" name="date" id="date_picker" class="form-control date-picker-input"
                               value="{{ $selectedDate }}" onchange="document.getElementById('dateForm').submit()">
                    </form>
                </div>

                <!-- Step 2: Time Slots -->
                <div>
                    <label class="form-label fw-bold mb-3">Step 2: Available Time Slots</label>

                    <h6 class="text-muted mb-3 mt-4 text-uppercase small fw-bold">Morning (9 AM - 12 PM)</h6>
                    <div class="slot-grid">
                        @foreach($morningSlots as $slot)
                            @php $isBooked = in_array($slot, $bookedSlots); @endphp
                            <button type="button" class="time-btn {{ $isBooked ? 'booked' : '' }}"
                                    {{ $isBooked ? 'disabled' : "onclick=openBookingModal('$slot')" }}>
                                {{ $slot }}
                            </button>
                        @endforeach
                    </div>

                    <h6 class="text-muted mb-3 mt-5 text-uppercase small fw-bold">Evening (4 PM - 9 PM)</h6>
                    <div class="slot-grid">
                        @foreach($eveningSlots as $slot)
                            @php $isBooked = in_array($slot, $bookedSlots); @endphp
                            <button type="button" class="time-btn {{ $isBooked ? 'booked' : '' }}"
                                    {{ $isBooked ? 'disabled' : "onclick=openBookingModal('$slot')" }}>
                                {{ $slot }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('appointments.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            @csrf
            <div class="modal-body p-5">
                <div class="text-center mb-4">
                    <div class="h1 text-primary">🏥</div>
                    <h4 class="fw-bold">Finalize Booking</h4>
                    <p class="text-muted">Fill in your details to confirm the appointment.</p>
                </div>

                <input type="hidden" name="appointment_time" id="modal_time">
                <input type="hidden" name="appointment_date" value="{{ $selectedDate }}">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Date & Time</label>
                    <div class="p-3 bg-light rounded-3 fw-bold">
                        📅 {{ \Carbon\Carbon::parse($selectedDate)->format('D, M d, Y') }} | ⏰ <span id="display_time"></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Your Name</label>
                    <input type="text" name="patient_name" class="form-control p-3 border-2" placeholder="Full Name" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control p-3 border-2" placeholder="Email Address" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 p-3 fw-bold rounded-3">Confirm Appointment</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#date_picker", {
        minDate: "today",
        dateFormat: "Y-m-d",
    });

    function openBookingModal(time) {
        document.getElementById('modal_time').value = time;
        document.getElementById('display_time').innerText = time;
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }
</script>

</body>
</html>
```

> Doctor sidebar + date picker + morning/evening slot grid + booking modal — all in one Blade file.

---

### STEP 8: Run the Application

Start the development server:

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

> Automatically redirects to `/book-appointment` and shows the booking page.

---

## HoursHelper Usage Reference

The `HoursHelper::create()` method generates time slots between two times with a given interval in minutes.

```php
// Syntax
HoursHelper::create('START_TIME', 'END_TIME', INTERVAL_IN_MINUTES);

// Examples used in this project
HoursHelper::create('09:00', '12:00', 20);  // Morning: every 20 minutes
HoursHelper::create('16:00', '21:00', 30);  // Evening: every 30 minutes
```

| Parameter | Example | Description |
|---|---|---|
| Start Time | `09:00` | Slot generation starts from this time |
| End Time | `12:00` | Slot generation stops at this time |
| Interval | `20` | Gap between each slot in minutes |

---

## Expected Output

| URL | What You See |
|---|---|
| `http://127.0.0.1:8000` | Redirects to `/book-appointment` |
| `http://127.0.0.1:8000/book-appointment` | Doctor profile + date picker + time slots |
| After selecting a date | Available (white) and Booked (gray) slots shown |
| After clicking a slot | Modal opens with name and email form |
| After confirming | Success message shown on page |

---

## Project Folder Structure

```
PHP_Laravel12_Hours_Helper/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── AppointmentController.php   ← Handles index + store logic
│   │
│   └── Models/
│       └── Appointment.php                 ← Appointment model
│
├── database/
│   └── migrations/
│       └── xxxx_create_appointments_table.php
│
├── resources/
│   └── views/
│       └── booking.blade.php               ← Main booking UI view
│
├── routes/
│   └── web.php                             ← GET + POST routes for booking
│
├── .env                                    ← DB connection config
├── artisan
├── composer.json
└── README.md
```

---

## Useful Commands

| Command | Purpose |
|---|---|
| `composer require label84/laravel-hours-helper` | Install the Hours Helper package |
| `php artisan make:model Appointment -m` | Create model and migration together |
| `php artisan migrate` | Run all migrations |
| `php artisan serve` | Start the local development server |

---