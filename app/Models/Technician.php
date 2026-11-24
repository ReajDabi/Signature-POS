<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use BelongsToBranch, HasFactory;

    protected $fillable = ['branch_id', 'user_id', 'name', 'commission_rate', 'is_active'];

    protected function casts(): array
    {
        return ['commission_rate' => 'decimal:2', 'is_active' => 'boolean'];
    }
}
