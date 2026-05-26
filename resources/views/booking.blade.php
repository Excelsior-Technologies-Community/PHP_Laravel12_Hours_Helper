<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Doctor Appointment Booking</title>

    <!-- Google Font -->

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Flatpickr -->

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background:
                radial-gradient(circle at top left, #172554, transparent 30%),
                radial-gradient(circle at bottom right, #0f766e, transparent 30%),
                #020617;

            font-family: 'Inter', sans-serif;
            color: white;
            min-height: 100vh;
        }

        .main-container {
            max-width: 1300px;
            margin: 40px auto;
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 28px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.35);
        }

        /* ========================= */
        /* DOCTOR CARD */
        /* ========================= */

        .doctor-card {
            text-align: center;
            overflow: hidden;
            position: relative;
        }

        .doctor-card::before {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(14, 165, 233, 0.12);
            top: -120px;
            right: -100px;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #38bdf8;
            box-shadow: 0 0 35px rgba(56, 189, 248, 0.4);
        }

        .speciality-badge {
            display: inline-block;
            margin-top: 12px;
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            padding: 10px 22px;
            border-radius: 50px;
            font-weight: 700;
        }

        .doctor-desc {
            color: #94a3b8;
            margin-top: 18px;
            line-height: 1.8;
        }

        /* ========================= */
        /* TOP STATS */
        /* ========================= */

        .stats-card {
            border-radius: 24px;
            padding: 28px;
            min-height: 160px;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-blue {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .stats-green {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .stats-orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 18px;
        }

        .stats-label {
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .stats-value {
            font-size: 34px;
            font-weight: 800;
            margin: 8px 0;
        }

        .stats-desc {
            opacity: 0.85;
        }

        /* ========================= */
        /* SEARCH */
        /* ========================= */

        .search-input {
            width: 250px;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid #1e293b;
            color: white;
            border-radius: 14px;
            padding: 12px 15px;
        }

        .search-input:focus {
            background: rgba(15, 23, 42, 0.9);
            color: white;
            border-color: #38bdf8;
            box-shadow: none;
        }

        .search-btn {
            border-radius: 14px;
            padding: 12px 20px;
            font-weight: 700;
        }

        /* ========================= */
        /* DATE */
        /* ========================= */

        .date-picker {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid #1e293b;
            color: white;
            border-radius: 16px;
            padding: 15px;
            font-weight: 700;
        }

        /* ========================= */
        /* SLOT BUTTON */
        /* ========================= */

        .section-title {
            margin-top: 35px;
            margin-bottom: 18px;
            color: #38bdf8;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(95px, 1fr));
            gap: 15px;
        }

        .time-btn {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid #1e293b;
            color: white;
            border-radius: 14px;
            padding: 14px;
            font-weight: 700;
            transition: 0.3s;
        }

        .time-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            transform: translateY(-3px);
        }

        .time-btn.active {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .time-btn.booked {
            background: #1e293b;
            color: #64748b;
            cursor: not-allowed;
        }

        /* ========================= */
        /* APPOINTMENT */
        /* ========================= */

        .appointment-card {
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 24px;
            padding: 22px 24px;
            margin-bottom: 20px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;

            transition: 0.35s ease;
        }

        .appointment-card:hover {
            transform: translateY(-4px);
            border-color: #0ea5e9;
            box-shadow: 0 10px 30px rgba(14,165,233,0.18);
        }

        .appointment-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .avatar-circle {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 24px;
            font-weight: 800;
            color: white;
        }

        .patient-name {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .patient-email {
            color: #94a3b8;
            font-size: 14px;
        }

        .appointment-center {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .info-item small {
            display: block;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .info-item strong {
            color: white;
        }

        .time-pill {
            background: rgba(34,197,94,0.18);
            color: #4ade80 !important;

            padding: 8px 14px;
            border-radius: 12px;
        }

        .delete-btn {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            border: none;
            color: white;
            padding: 12px 18px;
            border-radius: 14px;
            font-weight: 700;
        }

        .delete-btn:hover {
            transform: scale(1.04);
        }

        /* ========================= */
        /* PAGINATION */
        /* ========================= */

        .pagination {
            gap: 10px;
        }

        .pagination .page-link {
            width: 45px;
            height: 45px;

            border-radius: 14px !important;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(15, 23, 42, 0.9) !important;
            border: 1px solid #1e293b !important;

            color: white !important;
            font-weight: 700;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #0ea5e9, #2563eb) !important;
            border: none !important;
        }

        .pagination .page-item:first-child,
        .pagination .page-item:last-child {
            display: none;
        }

        /* ========================= */
        /* MODAL */
        /* ========================= */

        .modal-content {
            background: #0f172a;
            border-radius: 24px;
            border: 1px solid #1e293b;
            color: white;
        }

        .form-control {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid #1e293b;
            color: white;
            border-radius: 14px;
            padding: 14px;
        }

        .form-control:focus {
            background: rgba(15, 23, 42, 0.9);
            color: white;
            border-color: #38bdf8;
            box-shadow: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 700;
        }

        @media(max-width:768px) {

            .appointment-card {
                gap: 20px;
            }

            .appointment-center {
                width: 100%;
                justify-content: space-between;
            }

            .search-input {
                width: 180px;
            }
        }
    </style>

</head>

<body>

    <div class="container main-container">

        <div class="row g-4">

            <!-- LEFT -->

            <div class="col-lg-4">

                <div class="glass-card doctor-card">

                    <img src="https://img.freepik.com/free-photo/doctor-offering-medical-teleconsultation_23-2149329007.jpg"
                        class="profile-img mb-4">

                    <h2 class="fw-bold">
                        Dr. Sameer Patel
                    </h2>

                    <span class="speciality-badge">
                        Cardiologist
                    </span>

                    <p class="doctor-desc">
                        Specialist in cardiovascular treatment with 15+ years of experience.
                    </p>

                </div>

            </div>

            <!-- RIGHT -->

            <div class="col-lg-8">

                <!-- TOP 3 CARDS -->

                <div class="row g-4 mb-4">

                    <div class="col-md-4">

                        <div class="stats-card stats-blue">

                            <div class="stats-icon">
                                🩺
                            </div>

                            <small class="stats-label">
                                Experience
                            </small>

                            <div class="stats-value">
                                15+
                            </div>

                            <div class="stats-desc">
                                Years Experience
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="stats-card stats-green">

                            <div class="stats-icon">
                                👨‍⚕️
                            </div>

                            <small class="stats-label">
                                Patients
                            </small>

                            <div class="stats-value">
                                2000+
                            </div>

                            <div class="stats-desc">
                                Happy Patients
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="stats-card stats-orange">

                            <div class="stats-icon">
                                ⭐
                            </div>

                            <small class="stats-label">
                                Rating
                            </small>

                            <div class="stats-value">
                                4.9
                            </div>

                            <div class="stats-desc">
                                Excellent Reviews
                            </div>

                        </div>

                    </div>

                </div>

                <!-- BOOKING -->

                <div class="glass-card">

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

                        <h2 class="fw-bold">
                            Book Appointment
                        </h2>

                    </div>

                    <!-- DATE -->

                    <form action="{{ route('appointments.index') }}"
                        method="GET">

                        <input type="text"
                            name="date"
                            id="date_picker"
                            class="form-control date-picker mb-4"
                            value="{{ $selectedDate }}"
                            onchange="this.form.submit()">

                    </form>

                    <!-- MORNING -->

                    <div class="section-title">
                        Morning Slots
                    </div>

                    <div class="slot-grid">

                        @foreach($morningSlots as $slot)

                            @php
                                $isBooked = in_array($slot, $bookedSlots);
                            @endphp

                            <button type="button"
                                class="time-btn {{ $isBooked ? 'booked' : '' }}"
                                @if($isBooked)
                                    disabled
                                @else
                                    onclick="openBookingModal('{{ $slot }}', this)"
                                @endif>

                                {{ $slot }}

                            </button>

                        @endforeach

                    </div>

                    <!-- EVENING -->

                    <div class="section-title">
                        Evening Slots
                    </div>

                    <div class="slot-grid">

                        @foreach($eveningSlots as $slot)

                            @php
                                $isBooked = in_array($slot, $bookedSlots);
                            @endphp

                            <button type="button"
                                class="time-btn {{ $isBooked ? 'booked' : '' }}"
                                @if($isBooked)
                                    disabled
                                @else
                                    onclick="openBookingModal('{{ $slot }}', this)"
                                @endif>

                                {{ $slot }}

                            </button>

                        @endforeach

                    </div>

                </div>

                <!-- APPOINTMENT LIST -->

                <div class="glass-card mt-4">

                    <!-- TOP -->

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                        <div>

                            <h3 class="fw-bold mb-1">
                                Appointment List
                            </h3>

                            <small class="text-secondary">
                                Manage all booked appointments
                            </small>

                        </div>

                        <!-- SEARCH -->

                        <form action="{{ route('appointments.index') }}"
                            method="GET"
                            class="d-flex gap-2">

                            <input type="hidden"
                                name="date"
                                value="{{ request('date', $selectedDate) }}">

                            <input type="text"
                                name="search"
                                class="form-control search-input"
                                placeholder="Search patient..."
                                value="{{ request('search') }}">

                            <button class="btn btn-primary search-btn">
                                Search
                            </button>

                        </form>

                    </div>

                    <!-- TOTAL -->

                    <div class="mb-4">

                        <span class="badge bg-primary rounded-pill px-4 py-3 fs-6">

                            Total Appointments :
                            {{ $appointments->total() }}

                        </span>

                    </div>

                    <!-- LIST -->

                    @forelse($appointments as $appointment)

                        <div class="appointment-card">

                            <!-- LEFT -->

                            <div class="appointment-left">

                                <div class="avatar-circle">

                                    {{ strtoupper(substr($appointment->patient_name,0,1)) }}

                                </div>

                                <div>

                                    <h5 class="patient-name">

                                        {{ $appointment->patient_name }}

                                    </h5>

                                    <p class="patient-email mb-0">

                                        {{ $appointment->email }}

                                    </p>

                                </div>

                            </div>

                            <!-- CENTER -->

                            <div class="appointment-center">

                                <div class="info-item">

                                    <small>Date</small>

                                    <strong>

                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}

                                    </strong>

                                </div>

                                <div class="info-item">

                                    <small>Time</small>

                                    <strong class="time-pill">

                                        {{ date('h:i A', strtotime($appointment->appointment_time)) }}

                                    </strong>

                                </div>

                            </div>

                            <!-- RIGHT -->

                            <div>

                                <form action="{{ route('appointments.destroy', $appointment->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button class="delete-btn"
                                        onclick="return confirm('Delete appointment?')">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-5 text-secondary">

                            No appointments found

                        </div>

                    @endforelse

                    <!-- PAGINATION -->

                    <div class="d-flex justify-content-center mt-5">

                        {{ $appointments->onEachSide(1)->links('pagination::bootstrap-5') }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- MODAL -->

    <div class="modal fade"
        id="bookingModal">

        <div class="modal-dialog modal-dialog-centered">

            <form action="{{ route('appointments.store') }}"
                method="POST"
                class="modal-content p-4">

                @csrf

                <h3 class="fw-bold text-center mb-4">

                    Confirm Appointment

                </h3>

                <input type="hidden"
                    name="appointment_time"
                    id="modal_time">

                <input type="hidden"
                    name="appointment_date"
                    value="{{ $selectedDate }}">

                <div class="mb-3">

                    <input type="text"
                        name="patient_name"
                        class="form-control"
                        placeholder="Patient Name"
                        required>

                </div>

                <div class="mb-4">

                    <input type="email"
                        name="email"
                        class="form-control"
                        placeholder="Email Address"
                        required>

                </div>

                <button class="btn btn-primary w-100">

                    Confirm Booking

                </button>

            </form>

        </div>

    </div>

    <!-- Bootstrap -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Flatpickr -->

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>

        flatpickr("#date_picker", {
            minDate: "today",
            dateFormat: "Y-m-d",
        });

        function openBookingModal(time, el) {

            document.querySelectorAll('.time-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            el.classList.add('active');

            document.getElementById('modal_time').value = time;

            new bootstrap.Modal(
                document.getElementById('bookingModal')
            ).show();

        }

    </script>

</body>

</html>