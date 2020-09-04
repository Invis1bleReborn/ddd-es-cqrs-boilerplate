<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\DisableUser;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\GuardInterface;

/**
 * Class DisableUserGuard
 *
 * @package IdentityAccess\Ui\Identity\DisableUser
 */
class DisableUserGuard implements GuardInterface
{
    /**
     * {@inheritdoc}
     */
    public function isGranted(UserInterface $user, object $subject = null): bool
    {
        return true;
    }

}
