<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\EnableUser;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\ValueObject\Role;
use IdentityAccess\Ui\Access\RoleHierarchyAwareGuard;

/**
 * Class EnableUserGuard
 *
 * @package IdentityAccess\Ui\Identity\EnableUser
 */
class EnableUserGuard extends RoleHierarchyAwareGuard
{
    public function isGranted(UserInterface $user, object $subject = null): bool
    {
        return $this->isRoleReachable($user, new Role('ROLE_USER_ENABLER'));
    }

}
