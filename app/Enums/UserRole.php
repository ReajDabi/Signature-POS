<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case HqAdmin = 'hq_admin';
    case BranchManager = 'branch_manager';
    case BranchCashier = 'branch_cashier';

    public function label(): string
    {
        return match ($this) {
            self::HqAdmin => 'HQ Super Admin',
            self::BranchManager => 'Branch Manager',
            self::BranchCashier => 'Branch Cashier',
        };
    }
}
