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

namespace IdentityAccess\Application\Query\Identity\ListUsers;

use IdentityAccess\Infrastructure\Identity\Query\User;

/**
 * Trait UserModelAwareTrait.
 */
trait UserModelAwareTrait
{
    public static function getModelClass(): string
    {
        return User::class;
    }
}
