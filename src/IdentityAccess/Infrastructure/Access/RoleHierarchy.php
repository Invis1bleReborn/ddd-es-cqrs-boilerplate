<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access;

use IdentityAccess\Domain\Access\ValueObject\Role;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Access\RoleHierarchyInterface;

/**
 * Class RolesHierarchy
 *
 * @package IdentityAccess\Infrastructure\Access
 */
class RoleHierarchy implements RoleHierarchyInterface
{
    private \Symfony\Component\Security\Core\Role\RoleHierarchyInterface $roleHierarchy;

    public function __construct(\Symfony\Component\Security\Core\Role\RoleHierarchyInterface $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    public function reachableRoles(Roles $roles): Roles
    {
        return Roles::fromArray($this->roleHierarchy->getReachableRoleNames($roles->toArray()));
    }

    public function roleReachable(Roles $roles, Role $role): bool
    {
        return $this->reachableRoles($roles)
            ->contains($role);
    }

}
