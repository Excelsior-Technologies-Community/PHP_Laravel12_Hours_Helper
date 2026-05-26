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
        'status'

    ];

    protected $casts = [

        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',

    ];
}