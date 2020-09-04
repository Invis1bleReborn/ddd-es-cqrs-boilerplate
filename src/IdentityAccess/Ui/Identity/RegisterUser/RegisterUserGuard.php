<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\RegisterUser;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\GuardInterface;

/**
 * Class RegisterUserGuard
 *
 * @package IdentityAccess\Ui\Identity\RegisterUser
 */
class RegisterUserGuard implements GuardInterface
{
    /**
     * {@inheritdoc}
     */
    public function isGranted(UserInterface $user, object $subject = null): bool
    {
        return true;
    }

}
