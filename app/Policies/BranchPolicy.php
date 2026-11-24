<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isHq();
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->isHq() || $user->branch_id === $branch->id;
    }

    public function create(User $user): bool
    {
        return $user->isHq();
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->isHq();
    }

    public function delete(User $user, Branch $branch): bool
    {
        return false; // branches are deactivated, never deleted
    }
}
