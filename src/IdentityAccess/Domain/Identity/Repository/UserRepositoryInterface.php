<?php

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
