<?php

namespace App\Constants;

class InvoiceMessage
{
    public const EXAMINATION_ALREADY_EXISTS = 'This examination already has an invoice.';
    public const CREATED_SUCCESSFULLY = 'Invoice created successfully.';
    public const UPDATED_SUCCESSFULLY = 'Invoice updated successfully.';
    public const INSUFFICIENT_STOCK = 'Insufficient stock for medicine.';
    public const INACTIVE_MEDICINE = 'Inactive medicine cannot be prescribed.';
    
    public const INVALID_STATUS_FOR_UPDATE = 'Only unpaid invoices can be modified or cancelled.';
    public const DISCOUNT_EXCEEDS_SUBTOTAL = 'The discount amount cannot exceed the subtotal.';
    public const PAYMENT_COMPLETED = 'This invoice has already been paid and cannot be modified or cancelled.';
}