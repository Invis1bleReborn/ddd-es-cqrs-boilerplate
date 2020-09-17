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

namespace IdentityAccess\Infrastructure\Identity\Password;

use IdentityAccess\Domain\Identity\PasswordEncoderInterface;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;

/**
 * Class NoopPasswordEncoder.
 */
class NoopPasswordEncoder implements PasswordEncoderInterface
{
    public function encode(PlainPassword $plainPassword): HashedPassword
    {
        return HashedPassword::fromString($plainPassword->toString());
    }
}
