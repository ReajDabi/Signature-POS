<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Global HQ product catalog. Stock lives per branch in BranchInventory. */
class Product extends Model
{
    protected $fillable = ['name', 'unit', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
