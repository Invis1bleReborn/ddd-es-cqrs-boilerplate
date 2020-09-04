<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\EnableUser;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\GuardInterface;

/**
 * Class EnableUserGuard
 *
 * @package IdentityAccess\Ui\Identity\EnableUser
 */
class EnableUserGuard implements GuardInterface
{
    /**
     * {@inheritdoc}
     */
    public function isGranted(UserInterface $user, object $subject = null): bool
    {
        return true;
    }

}
