<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'full_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
    ];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
    ];
}