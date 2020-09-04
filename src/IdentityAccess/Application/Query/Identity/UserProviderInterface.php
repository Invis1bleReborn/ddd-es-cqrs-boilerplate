<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Query\Identity;

/**
 * Interface UserProviderInterface
 *
 * @package IdentityAccess\Application\Query\Identity
 */
interface UserProviderInterface
{
    /**
     * @param string $username
     *
     * @return UserInterface
     * @throws \RuntimeException
     */
    public function loadUserByUsername(string $username): UserInterface;

}
