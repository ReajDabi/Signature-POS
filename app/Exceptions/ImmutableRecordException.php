<?php

declare(strict_types=1);

namespace App\Exceptions;

final class ImmutableRecordException extends BusinessException
{
    public function __construct(string $message = 'This record cannot be changed or deleted.')
    {
        parent::__construct($message, 'IMMUTABLE_RECORD', 403);
    }
}
