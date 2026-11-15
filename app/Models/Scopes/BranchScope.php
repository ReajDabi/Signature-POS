<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Global Eloquent scope that restricts all queries to the authenticated
 * user's branch_id.  HQ Super Admins bypass this scope via their policy.
 *
 * Apply in every model that has a branch_id column:
 *
 *   protected static function booted(): void
 *   {
 *       static::addGlobalScope(new BranchScope());
 *   }
 */
class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // HQ Super Admins have branch_id === null; skip the scope for them.
        if (auth()->check() && auth()->user()->branch_id !== null) {
            $builder->where(
                $model->getTable().'.branch_id',
                auth()->user()->branch_id
            );
        }
    }
}
