<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        $this->log($model, 'created', [
            'attributes' => $this->safeAttributes($model->getAttributes()),
        ]);
    }

    public function updating(Model $model): void
    {
        if ($model instanceof Appointment && $model->isDirty('status')) {
            $this->log($model, 'status_changed', [
                'old_status' => $model->getOriginal('status'),
                'new_status' => $model->status,
            ]);
        }
    }

    public function updated(Model $model): void
    {
        $changes = $this->safeAttributes($model->getChanges());

        if ($model instanceof Appointment && $model->wasChanged('status')) {
            unset($changes['status']);
        }

        if (!empty($changes)) {
            $this->log($model, 'updated', [
                'changes' => $changes,
            ]);
        }
    }

    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted', [
            'attributes' => $this->safeAttributes($model->getAttributes()),
        ]);
    }

    private function log(Model $model, string $action, array $meta = []): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $model::class,
            'subject_id' => $model->getKey(),
            'meta' => $meta,
        ]);
    }

    private function safeAttributes(array $attributes): array
    {
        unset(
            $attributes['password'],
            $attributes['remember_token'],
            $attributes['created_at'],
            $attributes['updated_at'],
        );

        return $attributes;
    }
}
