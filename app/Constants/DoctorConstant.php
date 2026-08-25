<?php

namespace App\Constants;

class DoctorConstant
{
    // API Response messages
    public const MSG_GET_LIST_SUCCESS = 'Doctors retrieved successfully.';
    public const MSG_GET_DETAIL_SUCCESS = 'Doctor details retrieved successfully.';
    public const MSG_CREATE_SUCCESS = 'Doctor created successfully!';
    public const MSG_UPDATE_SUCCESS = 'Doctor updated successfully!';
    public const MSG_DELETE_SUCCESS = 'Doctor deleted successfully!';

    // Service exception messages
    public const MSG_NOT_FOUND = 'Doctor does not exist.';
    public const MSG_EMAIL_EXISTS = 'The email address is already taken by another doctor.';

    // Request Validation messages 
    public const MSG_USER_NOT_DOCTOR = 'The selected user is not a Doctor.';
    public const MSG_NAME_REQUIRED = 'Please enter the doctor name.';
    public const MSG_EMAIL_REQUIRED = 'Please enter the email address.';
    public const MSG_EMAIL_INVALID = 'The email address format is invalid.';
    public const MSG_SPECIALTY_REQUIRED = 'Please select a specialty.';
}