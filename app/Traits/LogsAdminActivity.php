<?php

namespace App\Traits;

use App\Models\AdminLogged;
use Illuminate\Support\Facades\Auth;

trait LogsAdminActivity
{
    protected function logActivity(
        string $action,
        string $description,
        ?string $targetType = null,
        $targetId = null,
        ?array $changes = null
    ): void {
        if (!Auth::guard('admin')->check()) {
            return;
        }

        AdminLogged::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => $action,
            'description' => $description,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changes' => $changes,
        ]);
    }

    protected function logLogin(): void
    {
        $this->logActivity(
            'login',
            Auth::guard('admin')->user()->name . ' logged in',
            null,
            null,
            null
        );
    }

    protected function logLogout(): void
    {
        $this->logActivity(
            'logout',
            Auth::guard('admin')->user()->name . ' logged out',
            null,
            null,
            null
        );
    }

    protected function logCreated(string $modelType, $modelId, string $description): void
    {
        $this->logActivity('created', $description, $modelType, $modelId);
    }

    protected function logUpdated(string $modelType, $modelId, string $description, ?array $changes = null): void
    {
        $this->logActivity('updated', $description, $modelType, $modelId, $changes);
    }

    protected function logDeleted(string $modelType, $modelId, string $description): void
    {
        $this->logActivity('deleted', $description, $modelType, $modelId);
    }

    protected function logViewed(string $modelType, $modelId, string $description): void
    {
        $this->logActivity('viewed', $description, $modelType, $modelId);
    }
}