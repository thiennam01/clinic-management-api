<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination_id',
        'invoice_code',
        'subtotal',
        'discount',
        'total',
        'status',
        'issued_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    /**
     * Invoice has many payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class);
    }
}