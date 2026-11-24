<?php

declare(strict_types=1);

namespace App\Support;

use Closure;

/**
 * The branch the current request or job is working for.
 *
 * null means "unscoped" (HQ users, console commands). It is bound as scoped,
 * so Laravel resets it for every request and queued job. Queued jobs that
 * touch branch data must wrap their work in run().
 */
final class TenantContext
{
    private ?int $branchId = null;

    public function set(?int $branchId): void
    {
        $this->branchId = $branchId;
    }

    public function branchId(): ?int
    {
        return $this->branchId;
    }

    public function isScoped(): bool
    {
        return $this->branchId !== null;
    }

    public function run(?int $branchId, Closure $callback): mixed
    {
        $previous = $this->branchId;
        $this->branchId = $branchId;

        try {
            return $callback();
        } finally {
            $this->branchId = $previous;
        }
    }
}
