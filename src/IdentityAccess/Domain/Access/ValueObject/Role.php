<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Access\ValueObject;

use MyCLabs\Enum\Enum;

/**
 * Class Role
 *
 * @package IdentityAccess\Domain\Access\ValueObject
 */
class Role extends Enum
{
    private const USER = 'ROLE_USER';

    private const SUPERADMIN = 'ROLE_SUPER_ADMIN';

}
