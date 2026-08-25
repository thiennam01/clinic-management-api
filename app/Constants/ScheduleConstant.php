<?php

namespace App\Constants;

class ScheduleConstant
{
    public const MSG_GET_LIST_SUCCESS = 'Schedules retrieved successfully.';
    public const MSG_CREATE_SUCCESS = 'Schedule created successfully!';
    public const MSG_NOT_FOUND = 'Schedule does not exist.';
    public const MSG_CONFLICT = 'The doctor already has a schedule conflicting with this timeframe.';

    public const MSG_INVALID_TIME_RANGE = 'The end time must be greater than the start time.';

    public const MSG_UPDATE_SUCCESS = 'Schedule updated successfully.';
    public const MSG_DELETE_SUCCESS = 'Schedule deleted successfully.';
    public const MSG_DELETE_HAS_APPOINTMENTS = 'Cannot delete a schedule that already has appointments.';
}