<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity;

use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;

/**
 * Interface PasswordEncoderInterface
 *
 * @package IdentityAccess\Domain\Identity
 */
interface PasswordEncoderInterface
{
    public function encode(PlainPassword $plainPassword): HashedPassword;

}
