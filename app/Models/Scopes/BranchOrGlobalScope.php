<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/** Catalog rows: global rows (branch_id null) plus the current branch's own. */
class BranchOrGlobalScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenant = app(TenantContext::class);

        if ($tenant->isScoped()) {
            $column = $model->qualifyColumn('branch_id');
            $builder->where(fn (Builder $q) => $q->whereNull($column)->orWhere($column, $tenant->branchId()));
        }
    }
}
