<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Exceptions\TenantViolationException;
use App\Models\Branch;
use App\Models\Scopes\BranchScope;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Scope;

/**
 * Tenant isolation for a model with a branch_id column:
 *  - reads are scoped to the current branch,
 *  - branch_id is filled from the context on create,
 *  - writing to another branch, or moving a row between branches, is refused.
 */
trait BelongsToBranch
{
    public static function bootBelongsToBranch(): void
    {
        static::addGlobalScope(static::tenantScope());

        static::creating(function (Model $model): void {
            $tenant = app(TenantContext::class);

            if (! $tenant->isScoped()) {
                return;
            }

            if ($model->getAttribute('branch_id') === null) {
                $model->setAttribute('branch_id', $tenant->branchId());

                return;
            }

            if ((int) $model->getAttribute('branch_id') !== $tenant->branchId()) {
                throw new TenantViolationException;
            }
        });

        static::updating(function (Model $model): void {
            if ($model->isDirty('branch_id')) {
                throw new TenantViolationException('A record cannot be moved to another branch.');
            }
        });
    }

    protected static function tenantScope(): Scope
    {
        return new BranchScope;
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
