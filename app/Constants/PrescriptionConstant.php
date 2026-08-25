<?php

namespace App\Constants;

class PrescriptionConstant
{
    // API Response Messages
    public const CREATED_SUCCESS = 'Prescription created successfully.';
    public const CREATE_FAILED = 'Failed to create prescription.';
    
    // Prescription Item Messages
    public const MEDICINE_ALREADY_EXISTS = 'Medicine already exists in this prescription.';
    public const ITEM_ADDED_SUCCESS = 'Prescription item added successfully.';
    public const ITEM_UPDATED_SUCCESS = 'Prescription item updated successfully.';
    public const ITEM_REMOVED_SUCCESS = 'Prescription item removed successfully.';
}