<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Access;

use IdentityAccess\Domain\Access\ValueObject\Role;
use IdentityAccess\Domain\Access\ValueObject\Roles;

/**
 * Interface RoleHierarchyInterface
 *
 * @package IdentityAccess\Domain\Access
 */
interface RoleHierarchyInterface
{
    public function reachableRoles(Roles $roles): Roles;

    public function subordinates(Roles $roles1, Roles $roles2): bool;

    public function roleReachable(Roles $roles, Role $role): bool;

}
