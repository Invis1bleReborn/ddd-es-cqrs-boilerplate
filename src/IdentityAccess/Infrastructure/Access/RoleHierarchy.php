<?php

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access;

use IdentityAccess\Domain\Access\ValueObject\Role;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Access\RoleHierarchyInterface;

/**
 * Class RolesHierarchy
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
