<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Identity\Password;

use IdentityAccess\Domain\Identity\PasswordEncoderInterface;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;

/**
 * Class NoopPasswordEncoder
 *
 * @package IdentityAccess\Infrastructure\Identity\Password
 */
class NoopPasswordEncoder implements PasswordEncoderInterface
{
    public function encode(PlainPassword $plainPassword): HashedPassword
    {
        return HashedPassword::fromString($plainPassword->toString());
    }

}
