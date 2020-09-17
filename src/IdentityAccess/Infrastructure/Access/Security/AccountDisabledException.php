<?php

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Security;

use IdentityAccess\Ui\Access\AccountStatusExceptionInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

/**
 * Class AccountDisabledException
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
