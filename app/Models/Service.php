<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use App\Models\Scopes\BranchOrGlobalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Scope;

/** branch_id null = global HQ catalog entry; otherwise a branch-only service. */
class Service extends Model
{
    use BelongsToBranch, HasFactory;

    protected $fillable = ['branch_id', 'name', 'category', 'price', 'duration_min', 'is_active'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'is_active' => 'boolean'];
    }

    protected static function tenantScope(): Scope
    {
        return new BranchOrGlobalScope;
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'service_products')->withPivot('qty_consumed');
    }
}
