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

namespace IdentityAccess\Domain\Identity\Repository;

use IdentityAccess\Domain\Identity\User;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Interface UserRepositoryInterface
 *
 * @package IdentityAccess\Domain\Identity\Repository
 */
interface UserRepositoryInterface
{
    public function get(UserId $id): User;

    public function store(User $user): void;

}
