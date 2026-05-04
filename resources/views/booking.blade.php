<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment Booking</title>

    <!-- Fonts + CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8, #e0f2fe);
            font-family: 'Inter', sans-serif;
        }

        .main-container {
            max-width: 1100px;
            margin: 50px auto;
        }

        .card-box {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.05);
        }

        .profile-img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 4px solid #e2e8f0;
        }

        .badge-specialty {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Slots */
        .slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 12px;
        }

        .time-btn {
            border: none;
            background: #f1f5f9;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.2s;
        }

        .time-btn:hover:not(:disabled) {
            background: #0ea5e9;
            color: white;
            transform: scale(1.05);
        }

        .time-btn.active {
            background: #0284c7;
            color: white;
        }

        .time-btn.booked {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .date-picker-input {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #64748b;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container main-container">
    <div class="row g-4">

        <!-- Doctor -->
        <div class="col-lg-4">
            <div class="card-box text-center">
                <img src="https://img.freepik.com/free-photo/doctor-offering-medical-teleconsultation_23-2149329007.jpg" class="profile-img mb-3">
                <h4 class="fw-bold">Dr. Sameer Patel</h4>
                <span class="badge-specialty">Cardiologist</span>

                <p class="text-muted mt-3">15+ years experience in heart care.</p>

                <div class="d-flex justify-content-around mt-4">
                    <div><small>Experience</small><br><strong>15+ yrs</strong></div>
                    <div><small>Patients</small><br><strong>2000+</strong></div>
                    <div><small>Rating</small><br><strong>⭐ 4.9</strong></div>
                </div>
            </div>
        </div>

        <!-- Booking -->
        <div class="col-lg-8">
            <div class="card-box">

                <h3 class="fw-bold mb-4">Book Appointment</h3>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Date -->
                <form action="{{ route('appointments.index') }}" method="GET">
                    <input type="text" name="date" id="date_picker"
                        class="form-control date-picker-input mb-4"
                        value="{{ $selectedDate }}"
                        onchange="this.form.submit()">
                </form>

                <!-- Morning -->
                <div class="section-title">Morning (9AM - 12PM)</div>
                <div class="slot-grid">
                    @foreach($morningSlots as $slot)
                        @php $isBooked = in_array($slot, $bookedSlots); @endphp

                        <button type="button"
                            class="time-btn {{ $isBooked ? 'booked' : '' }}"
                            @if($isBooked)
                                disabled
                            @else
                                onclick="openBookingModal('{{ $slot }}', this)"
                            @endif
                        >
                            {{ $slot }}
                        </button>
                    @endforeach
                </div>

                <!-- Evening -->
                <div class="section-title">Evening (4PM - 9PM)</div>
                <div class="slot-grid">
                    @foreach($eveningSlots as $slot)
                        @php $isBooked = in_array($slot, $bookedSlots); @endphp

                        <button type="button"
                            class="time-btn {{ $isBooked ? 'booked' : '' }}"
                            @if($isBooked)
                                disabled
                            @else
                                onclick="openBookingModal('{{ $slot }}', this)"
                            @endif
                        >
                            {{ $slot }}
                        </button>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="bookingModal">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('appointments.store') }}" method="POST" class="modal-content p-4">
            @csrf

            <h4 class="fw-bold text-center mb-3">Confirm Booking</h4>

            <input type="hidden" name="appointment_time" id="modal_time">
            <input type="hidden" name="appointment_date" value="{{ $selectedDate }}">

            <div class="mb-3 text-center">
                <strong id="display_time"></strong>
            </div>

            <input type="text" name="patient_name" class="form-control mb-3" placeholder="Your Name" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

            <button class="btn btn-primary w-100">Confirm Appointment</button>
        </form>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
flatpickr("#date_picker", {
    minDate: "today",
    dateFormat: "Y-m-d",
});

function openBookingModal(time, el) {

    // remove old active
    document.querySelectorAll('.time-btn').forEach(btn => btn.classList.remove('active'));

    // add active
    el.classList.add('active');

    document.getElementById('modal_time').value = time;
    document.getElementById('display_time').innerText = time;

    new bootstrap.Modal(document.getElementById('bookingModal')).show();
}
</script>

</body>
</html>