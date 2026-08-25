<?php

namespace App\Constants;

class PatientConstant
{
    // API & Service Messages
    public const MSG_GET_LIST_SUCCESS = 'Patients retrieved successfully.';
    public const MSG_GET_DETAIL_SUCCESS = 'Patient details retrieved successfully.';
    public const MSG_CREATE_SUCCESS = 'Patient created successfully!';
    public const MSG_UPDATE_SUCCESS = 'Patient updated successfully!';
    public const MSG_DELETE_SUCCESS = 'Patient deleted successfully!';

    public const MSG_NOT_FOUND = 'Patient does not exist.';
    public const MSG_USER_NOT_PATIENT = 'The selected user is not a Patient.';
}