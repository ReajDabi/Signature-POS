<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    use BelongsToBranch;

    protected $table = 'imports';

    protected $fillable = [
        'branch_id', 'user_id', 'type', 'filename', 'total_rows', 'imported', 'failed', 'status', 'error_log',
    ];

    protected function casts(): array
    {
        return ['error_log' => 'array'];
    }
}
