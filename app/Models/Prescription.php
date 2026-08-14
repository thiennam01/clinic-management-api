<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'examination_id',
        'doctor_id',
        'notes',
    ];

    /**
     * Get the examination associated with the prescription.
     */
    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    /**
     * Get the doctor who created the prescription.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Get the items belonging to the prescription.
     */
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}