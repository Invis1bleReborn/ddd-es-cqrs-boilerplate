<?php

declare(strict_types=1);

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
