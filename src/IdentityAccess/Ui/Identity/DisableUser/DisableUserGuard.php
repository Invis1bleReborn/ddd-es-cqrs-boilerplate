<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\DisableUser;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\ValueObject\Role;
use IdentityAccess\Ui\Access\RoleHierarchyAwareGuard;

/**
 * Class DisableUserGuard
 *
 * @package IdentityAccess\Ui\Identity\DisableUser
 */
class DisableUserGuard extends RoleHierarchyAwareGuard
{
    public function isGranted(UserInterface $user, object $subject = null): bool
    {
        return $this->isRoleReachable($user, new Role('ROLE_USER_DISABLER'));
    }

}
