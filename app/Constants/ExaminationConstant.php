<?php

namespace App\Constants;

class ExaminationConstant
{
    public const MSG_GET_LIST_SUCCESS = 'Examinations retrieved successfully.';
    public const MSG_GET_DETAIL_SUCCESS = 'Examination details retrieved successfully.';
    public const MSG_CREATE_SUCCESS = 'Examination created successfully from appointment!';
    
    public const MSG_NOT_FOUND = 'Examination does not exist.';
    public const MSG_APPOINTMENT_NOT_FOUND = 'Appointment does not exist.';
    
    // Notification to block cancelled, completed, or invalid schedules
    public const MSG_APPOINTMENT_NOT_CONFIRMED = 'Cannot create an examination from an appointment that is not confirmed, already completed, or cancelled.';
    
    public const MSG_EXAMINATION_ALREADY_EXISTS = 'This appointment already has an examination record.';
    public const MSG_UPDATE_APPOINTMENT_FAILED = 'Failed to update appointment status.';
}