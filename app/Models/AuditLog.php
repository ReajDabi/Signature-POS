<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\ImmutableRecordException;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;

/** Append-only. Written through App\Services\AuditLogger; never updated or deleted. */
class AuditLog extends Model
{
    use BelongsToBranch;

    public const UPDATED_AT = null;

    protected $fillable = [
        'branch_id', 'user_id', 'action', 'subject_type', 'subject_id', 'old_value', 'new_value', 'ip_address',
    ];

    protected function casts(): array
    {
        return ['old_value' => 'array', 'new_value' => 'array'];
    }

    protected static function booted(): void
    {
        static::updating(fn () => throw new ImmutableRecordException('Audit records cannot be changed.'));
        static::deleting(fn () => throw new ImmutableRecordException('Audit records cannot be deleted.'));
    }
}
