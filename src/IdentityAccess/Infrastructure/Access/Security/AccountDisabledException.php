<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Security;

use IdentityAccess\Ui\Access\AccountStatusExceptionInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

/**
 * Class AccountDisabledException
 *
 * @package IdentityAccess\Infrastructure\Access\Security
 */
class AccountDisabledException extends CustomUserMessageAccountStatusException implements
    AccountStatusExceptionInterface
{
    public function __construct(
        string $message = null,
        array $messageData = [],
        int $code = 0,
        \Throwable $previous = null
    )
    {
        parent::__construct(
            $message ?? 'Account is disabled.',
            $messageData,
            $code,
            $previous
        );
    }

}
