<?php

namespace App\Constants;

class PrescriptionConstant
{
    // API Response Messages
    public const CREATED_SUCCESS = 'Prescription created successfully.';
    public const CREATE_FAILED = 'Failed to create prescription.';
    public const NOT_FOUND = 'Prescription not found.';

    // Prescription Item Messages
    public const MEDICINE_ALREADY_EXISTS = 'Medicine already exists in this prescription.';
    public const ITEM_ADDED_SUCCESS = 'Prescription item added successfully.';
    public const ITEM_UPDATED_SUCCESS = 'Prescription item updated successfully.';
    public const ITEM_REMOVED_SUCCESS = 'Prescription item removed successfully.';

    // Stock & Medicine Messages
    public const MEDICINE_NOT_FOUND = 'Medicine not found or inactive.';
    public const INSUFFICIENT_STOCK = 'Insufficient stock for medicine: ';
}