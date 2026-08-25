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
    public const MSG_UPDATE_SUCCESS = 'The appointment date update successfully!';
    public const MSG_SCHEDULE_NOT_AVAILABLE = 'No available work schedule matches the selected doctor, date, and time.';

    public const CODE_PREFIX = 'APT-';
    public const PAGINATE_LIMIT = 10;

    // Appointment Statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';


    public const MSG_DELETE_SUCCESS = 'Xóa lịch khám thành công.';
    /**
     * Lấy danh sách các trạng thái lịch hẹn
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Chờ xác nhận',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_COMPLETED => 'Đã hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy',
        ];
    }
}