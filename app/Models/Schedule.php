<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'max_patients',
        'current_patients',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_active' => 'boolean',
        'max_patients' => 'integer',
        'current_patients' => 'integer',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}