<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\RoleHierarchyInterface;
use IdentityAccess\Domain\Access\ValueObject\Role;
use IdentityAccess\Domain\Access\ValueObject\Roles;

/**
 * Class RoleHierarchyAwareGuard
 *
 * @package IdentityAccess\Ui\Access
 */
abstract class RoleHierarchyAwareGuard implements GuardInterface
{
    private RoleHierarchyInterface $roleHierarchy;

    public function __construct(RoleHierarchyInterface $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    protected function isRoleReachable(UserInterface $user, Role $role): bool
    {
        return $this->roleHierarchy->roleReachable(
            Roles::fromArray($user->getRoles()),
            $role
        );
    }

}
