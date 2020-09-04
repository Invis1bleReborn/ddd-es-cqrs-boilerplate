<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity;

use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Class UserTransformer
 *
 * @package IdentityAccess\Ui\Identity
 */
class UserTransformer
{
    public function __invoke(UserInterface $user): UserView
    {
        return new UserView(
            $user->getId(),
            $user->getEmail(),
            $user->getRoles(),
            $user->isEnabled(),
            $user->getRegisteredById(),
            $user->getDateRegistered(),
        );
    }

}
