<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Query\Access;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\ValueObject\AccessToken;

/**
 * Interface AccessTokenGeneratorInterface
 *
 * @package IdentityAccess\Application\Query\Access
 */
interface AccessTokenGeneratorInterface
{
    public function __invoke(UserInterface $user): AccessToken;

}
