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

namespace IdentityAccess\Domain\Identity\Exception;

use Common\Shared\Domain\Exception\StateTransitionException;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserStateTransitionException.
 */
class UserStateTransitionException extends StateTransitionException
{
    public function __construct(
        UserId $userId,
        string $state,
        string $transition,
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct(
            sprintf('Invalid transition %s for state %s of user %s.', $userId->toString(), $state, $transition),
            $code,
            $previous
        );
    }
}
