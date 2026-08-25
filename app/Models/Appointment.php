<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'schedule_id',
        'appointment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function schedule()
    {   
        return $this->belongsTo(Schedule::class);
    }

    public function examination()
    {
        return $this->hasOne(Examination::class);
    }   
}