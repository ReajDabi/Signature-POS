<?php

declare(strict_types=1);

namespace App\Exceptions;

final class TenantViolationException extends BusinessException
{
    public function __construct(string $message = 'You do not have access to that branch.')
    {
        parent::__construct($message, 'TENANT_VIOLATION', 403);
    }
}
