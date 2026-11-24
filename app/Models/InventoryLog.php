<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;

/** Stock movement ledger: one row per change to BranchInventory. */
class InventoryLog extends Model
{
    use BelongsToBranch;

    public const UPDATED_AT = null;

    protected $fillable = ['branch_id', 'product_id', 'transaction_item_id', 'user_id', 'change_qty', 'reason'];

    protected function casts(): array
    {
        return ['change_qty' => 'decimal:2'];
    }
}
