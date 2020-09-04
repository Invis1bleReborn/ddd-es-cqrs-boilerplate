<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Infrastructure\Access\Security\AccountDisabledException;

/**
 * Class UserChecker
 *
 * @package IdentityAccess\Infrastructure\Access\Security
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(UserInterface $user): void
    {
        if ($user->isEnabled()) {
            return;
        }

        throw new AccountDisabledException();
    }

}
