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

    public function subordinates(Roles $roles1, Roles $roles2): bool
    {
        $reachableRoles1 = $this->roleHierarchy->getReachableRoleNames($roles1->toArray());
        $reachableRoles2 = $this->roleHierarchy->getReachableRoleNames($roles2->toArray());

        if (count($reachableRoles1) <= count($reachableRoles2)) {
            return false;
        }

        if (empty(array_diff($reachableRoles1, $reachableRoles2))) {
            return true;
        }

        return false;
    }

    public function roleReachable(Roles $roles, Role $role): bool
    {
        return $this->reachableRoles($roles)
            ->contains($role);
    }

}
