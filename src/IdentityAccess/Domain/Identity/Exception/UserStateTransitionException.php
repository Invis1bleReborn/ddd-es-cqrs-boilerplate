<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\Exception;

use Common\Shared\Domain\Exception\StateTransitionException;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserStateTransitionException
 *
 * @package IdentityAccess\Domain\Identity\Exception
 */
class UserStateTransitionException extends StateTransitionException
{
    public function __construct(
        UserId $userId,
        string $state,
        string $transition,
        int $code = 0,
        \Throwable $previous = null
    )
    {
        parent::__construct(
            sprintf('Invalid transition %s for state %s of user %s.', $userId->toString(), $state, $transition),
            $code,
            $previous
        );
    }

}
