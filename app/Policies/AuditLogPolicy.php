<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isHq() || $user->hasRole(UserRole::BranchManager->value);
    }

    public function view(User $user, AuditLog $log): bool
    {
        return $user->isHq()
            || ($user->hasRole(UserRole::BranchManager->value) && $user->branch_id === $log->branch_id);
    }

    public function create(User $user): bool
    {
        return false; // written only by AuditLogger
    }

    public function update(User $user, AuditLog $log): bool
    {
        return false;
    }

    public function delete(User $user, AuditLog $log): bool
    {
        return false;
    }
}
