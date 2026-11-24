<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use BelongsToBranch;

    protected $fillable = ['branch_id', 'transaction_item_id', 'technician_id', 'rate', 'amount', 'is_paid', 'paid_at'];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'amount' => 'decimal:2',
            'is_paid' => 'boolean',
            'paid_at' => 'datetime',
        ];
    }
}
