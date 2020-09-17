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

namespace IdentityAccess\Domain\Access\ValueObject;

use MyCLabs\Enum\Enum;

/**
 * Class Role
 */
class Role extends Enum
{
    private const SUPERADMIN = 'ROLE_SUPER_ADMIN';

    private const ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    private const USER = 'ROLE_USER';

    private const USER_DISABLER = 'ROLE_USER_DISABLER';

    private const USER_ENABLER = 'ROLE_USER_ENABLER';
}
