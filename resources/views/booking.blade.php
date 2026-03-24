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
        
        /* Time Slots Styling */
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

        <div class="col-lg-8">
            <div class="booking-content">
                <h3 class="fw-bold mb-4">Book an Appointment</h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-5">
                    <label class="form-label fw-bold mb-3">Step 1: Select Date</label>
                    <form id="dateForm" action="{{ route('appointments.index') }}" method="GET">
                        <input type="text" name="date" id="date_picker" class="form-control date-picker-input" value="{{ $selectedDate }}" onchange="document.getElementById('dateForm').submit()">
                    </form>
                </div>

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
    // Initialize Calendar
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