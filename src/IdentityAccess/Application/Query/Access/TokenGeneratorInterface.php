<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Query\Access;

use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Interface TokenGeneratorInterface
 *
 * @package IdentityAccess\Application\Query\Access
 */
interface TokenGeneratorInterface
{
    public function __invoke(UserInterface $user): TokenInterface;

}
