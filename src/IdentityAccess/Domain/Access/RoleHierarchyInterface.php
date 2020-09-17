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

namespace IdentityAccess\Domain\Access;

use IdentityAccess\Domain\Access\ValueObject\Role;
use IdentityAccess\Domain\Access\ValueObject\Roles;

/**
 * Interface RoleHierarchyInterface
 *
 */
interface RoleHierarchyInterface
{
    public function reachableRoles(Roles $roles): Roles;

    public function roleReachable(Roles $roles, Role $role): bool;
}
