<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Examination extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'examinations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'diagnosis',
        'notes',
        'examined_at',
    ];

    /**
     * Get the appointment associated with the examination.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the doctor who performed the examination.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class); // Adjust model name if your project uses User for doctors
    }

    /**
     * Get the patient who received the examination.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class); // Adjust model name if your project uses User for patients
    }
}