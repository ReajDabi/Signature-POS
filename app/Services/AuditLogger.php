<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AuditLog;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/** The only place audit rows are written. Call it inside the same DB transaction as the change. */
final class AuditLogger
{
    public function __construct(private readonly TenantContext $tenant) {}

    public function record(string $action, ?Model $subject = null, ?array $old = null, ?array $new = null): AuditLog
    {
        return AuditLog::create([
            'branch_id' => $this->tenant->branchId() ?? $subject?->getAttribute('branch_id'),
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject ? class_basename($subject) : null,
            'subject_id' => $subject?->getKey(),
            'old_value' => $old,
            'new_value' => $new,
            'ip_address' => request()?->ip(),
        ]);
    }
}
