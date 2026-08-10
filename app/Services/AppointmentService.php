<?php

namespace App\Services;

use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Exception;

class AppointmentService
{
    public function __construct(
        protected AppointmentRepositoryInterface $appointmentRepository,
        protected ScheduleRepositoryInterface $scheduleRepository
    ) {}

    public function getAppointments(array $filters, int $perPage = 10)
    {
        return $this->appointmentRepository->paginate($filters, $perPage);
    }

    public function createAppointment(array $data)
    {
        // 1. Kiểm tra lịch làm việc có tồn tại không
        $schedule = $this->scheduleRepository->find($data['schedule_id']);
        if (!$schedule) {
            throw new Exception('Lịch làm việc không tồn tại.', 404);
        }

        // 2. Kiểm tra xem lịch đã đủ số lượng bệnh nhân (max_patients) chưa
        $currentBookings = $this->appointmentRepository->countBySchedule($data['schedule_id']);
        if ($currentBookings >= $schedule->max_patients) {
            throw new Exception('Khung giờ này đã hết chỗ.', 422);
        }

        // 3. Gán patient_id từ user đang đăng nhập (hoặc truyền vào)
        return $this->appointmentRepository->create($data);
    }

    public function updateStatus($id, string $newStatus)
    {
        // 1. Tìm lịch hẹn thông qua Repository
        $appointment = $this->appointmentRepository->find($id);
        if (!$appointment) {
            throw new Exception('Lịch khám không tồn tại.', 404);
        }

        $currentStatus = $appointment->status ?? 'pending';

        // 2. Định nghĩa quy tắc máy trạng thái (State Machine) theo Task #19
        $allowedTransitions = [
            'pending'   => ['scheduled', 'confirmed', 'cancelled'],
            'scheduled' => ['confirmed', 'cancelled'],
            'confirmed' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        // 3. Kiểm tra tính hợp lệ của bước chuyển trạng thái
        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            throw new Exception("Không thể chuyển trạng thái từ '{$currentStatus}' sang '{$newStatus}'.", 422);
        }

        // 4. Cập nhật trạng thái thông qua Repository (hoặc save trực tiếp nếu repository hỗ trợ update)
        return $this->appointmentRepository->update($id, ['status' => $newStatus]);
    }
}   