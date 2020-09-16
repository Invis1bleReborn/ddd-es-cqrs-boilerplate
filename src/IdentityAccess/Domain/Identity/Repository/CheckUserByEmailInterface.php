<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IdentityAccess\Domain\Identity\Repository;

use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Interface CheckUserByEmailInterface
 *
 * @package IdentityAccess\Domain\Identity\Repository
 */
interface CheckUserByEmailInterface
{
    public function existsEmail(Email $email): ?UserId;

}
