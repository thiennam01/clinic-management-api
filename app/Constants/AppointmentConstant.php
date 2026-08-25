<?php

namespace App\Constants;

class AppointmentConstant
{
    // API & Service Messages
    public const MSG_GET_LIST_SUCCESS = 'Appointments retrieved successfully.';
    public const MSG_CREATE_SUCCESS = 'Appointment created successfully!';
    public const MSG_UPDATE_STATUS_SUCCESS = 'Appointment status updated successfully!';
    
    public const MSG_SCHEDULE_NOT_FOUND = 'Work schedule does not exist.';
    public const MSG_SCHEDULE_FULL = 'This time slot is fully booked.';
    public const MSG_DOCTOR_CONFLICT = 'The doctor already has another appointment overlapping with this time slot.';
    public const MSG_APPOINTMENT_NOT_FOUND = 'Appointment does not exist.';
    public const MSG_INVALID_STATUS_TRANSITION = "Cannot transition status from '%s' to '%s'.";

    // Request Validation Messages
    public const MSG_SCHEDULE_REQUIRED = 'Please select a work schedule.';
    public const MSG_SCHEDULE_EXISTS = 'The selected work schedule does not exist.';
    public const MSG_DATE_REQUIRED = 'Please select the appointment date and time.';
    public const MSG_DATE_INVALID = 'The appointment date format is invalid.';
    public const MSG_DATE_AFTER_OR_EQUAL = 'The appointment date must be today or a future date.';
}