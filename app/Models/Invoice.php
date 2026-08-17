<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}