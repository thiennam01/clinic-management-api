<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $logs = ActivityLog::query()
            ->whereIn('action', [
                'created',
                'status_changed',
                'deleted',
            ])
            ->where('created_at', '>=', now()->subDays(30))
            ->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('notification_reads')
                    ->whereColumn(
                        'notification_reads.activity_log_id',
                        'activity_logs.id'
                    )
                    ->where('notification_reads.user_id', $userId);
            })
            ->latest()
            ->take(20)
            ->get();

        $allLogs = ActivityLog::query()
            ->whereIn('action', [
                'created',
                'status_changed',
                'deleted',
            ])
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->take(20)
            ->get();

        return response()->json([
            'unread_count' => $logs->count(),
            'notifications' => $allLogs->map(
                fn (ActivityLog $log) => $this->formatNotification($log)
            )->values(),
        ]);
    }

    public function readAll(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $logs = ActivityLog::query()
            ->whereIn('action', [
                'created',
                'status_changed',
                'deleted',
            ])
            ->where('created_at', '>=', now()->subDays(30))
            ->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('notification_reads')
                    ->whereColumn(
                        'notification_reads.activity_log_id',
                        'activity_logs.id'
                    )
                    ->where('notification_reads.user_id', $userId);
            })
            ->pluck('id');

        if ($logs->isNotEmpty()) {
            $now = now();

            DB::table('notification_reads')->insertOrIgnore(
                $logs->map(fn ($logId) => [
                    'user_id' => $userId,
                    'activity_log_id' => $logId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all()
            );
        }

        return response()->json([
            'unread_count' => 0,
        ]);
    }

    private function formatNotification(ActivityLog $log): array
    {
        $subject = class_basename($log->subject_type);

        $message = match ($log->action) {
            'status_changed' => $this->statusChangedMessage($subject, $log),
            'created' => $this->createdMessage($subject),
            'deleted' => $this->deletedMessage($subject),
            default => 'Có hoạt động mới trong hệ thống.',
        };

        return [
            'id' => $log->id,
            'message' => $message,
            'action' => $log->action,
            'subject_type' => $subject,
            'subject_id' => $log->subject_id,
            'created_at' => $log->created_at->diffForHumans(),
            'created_at_iso' => $log->created_at->toIso8601String(),
        ];
    }

    private function statusChangedMessage(
        string $subject,
        ActivityLog $log
    ): string {
        if ($subject === 'Appointment') {
            $old = $log->meta['old_status'] ?? null;
            $new = $log->meta['new_status'] ?? null;

            return "Lịch khám #{$log->subject_id} đã chuyển từ {$old} sang {$new}.";
        }

        return "Trạng thái {$subject} #{$log->subject_id} đã được thay đổi.";
    }

    private function createdMessage(string $subject): string
    {
        return match ($subject) {
            'Examination' => 'Đã tạo phiếu khám mới.',
            'Prescription' => 'Đã tạo đơn thuốc mới.',
            'Invoice' => 'Đã tạo hóa đơn mới.',
            'Payment' => 'Đã ghi nhận thanh toán mới.',
            'Appointment' => 'Đã tạo lịch khám mới.',
            'User' => 'Đã tạo người dùng mới.',
            default => "Đã tạo {$subject} mới.",
        };
    }

    private function deletedMessage(string $subject): string
    {
        return match ($subject) {
            'Appointment' => 'Một lịch khám đã bị xóa.',
            'User' => 'Một người dùng đã bị xóa.',
            default => "{$subject} #{$subject} đã bị xóa.",
        };
    }
}