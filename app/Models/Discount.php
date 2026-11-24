<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DiscountType;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use BelongsToBranch;

    protected $fillable = ['branch_id', 'label', 'type', 'value', 'is_active'];

    protected function casts(): array
    {
        return ['type' => DiscountType::class, 'value' => 'decimal:2', 'is_active' => 'boolean'];
    }
}
