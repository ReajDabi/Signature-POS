<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchInventory extends Model
{
    use BelongsToBranch;

    protected $table = 'branch_inventory';

    protected $fillable = ['branch_id', 'product_id', 'qty_on_hand', 'low_stock_threshold'];

    protected function casts(): array
    {
        return ['qty_on_hand' => 'decimal:2', 'low_stock_threshold' => 'decimal:2'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isLow(): bool
    {
        return Money::compare($this->qty_on_hand, $this->low_stock_threshold) <= 0;
    }
}
