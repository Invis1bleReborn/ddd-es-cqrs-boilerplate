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

namespace IdentityAccess\Domain\Identity;

use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;

/**
 * Interface PasswordCheckerInterface
 *
 * @package IdentityAccess\Domain\Identity
 */
interface PasswordCheckerInterface
{
    /**
     * @param HashedPassword $hashedPassword
     * @param PlainPassword  $plainPassword
     * @param string|null    $salt
     *
     * @throws \RuntimeException
     */
    public function check(HashedPassword $hashedPassword, PlainPassword $plainPassword, ?string $salt): void;

}
