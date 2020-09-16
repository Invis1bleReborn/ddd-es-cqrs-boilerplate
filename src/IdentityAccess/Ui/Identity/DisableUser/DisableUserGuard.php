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
