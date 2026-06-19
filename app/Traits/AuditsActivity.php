<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait AuditsActivity
{
    protected static function bootAuditsActivity()
    {
        static::created(function ($model) {
            self::logAction('create', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = array_intersect_key($model->getOriginal(), $changes);
            self::logAction('update', $model, $original, $changes);
        });

        static::deleted(function ($model) {
            self::logAction('delete', $model, $model->getAttributes(), null);
        });
    }

    public static function logAction($action, $model, $oldValues = null, $newValues = null, $deskripsi = null)
    {
        if (app()->runningInConsole() && !app()->runningUnitTests()) return; // Skip if seeding/artisan

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id ?? null,
            'deskripsi' => $deskripsi ?? "Melakukan aksi {$action} pada " . class_basename($model),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logCustomAction($action, $modelType, $modelId, $deskripsi)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
