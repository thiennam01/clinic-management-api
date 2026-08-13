<?php

namespace App\Constants;

class MedicineMessage
{
    public const NOT_FOUND = 'Medicine not found in the system.';
    public const CREATE_SUCCESS = 'Medicine created successfully.';
    public const UPDATE_SUCCESS = 'Medicine updated successfully.';
    public const DELETE_SUCCESS = 'Medicine deleted successfully.';
    
    public static function inactivePrescription(string $name, string $code): string
    {
        return "The medicine '{$name}' (Code: {$code}) is currently inactive and cannot be prescribed in a new order.";
    }
}