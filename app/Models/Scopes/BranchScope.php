<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/** Limits every query to the current branch. No-op when unscoped (HQ). */
class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenant = app(TenantContext::class);

        if ($tenant->isScoped()) {
            $builder->where($model->qualifyColumn('branch_id'), $tenant->branchId());
        }
    }
}
