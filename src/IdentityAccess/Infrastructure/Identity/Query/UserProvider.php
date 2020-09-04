<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Identity\Query;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Application\Query\Identity\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface as SecurityUserProviderInterface;

/**
 * Class UserProvider
 *
 * @package IdentityAccess\Infrastructure\Identity\Query
 */
final class UserProvider implements UserProviderInterface
{
    private SecurityUserProviderInterface $userProvider;

    public function __construct(SecurityUserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        $user = $this->userProvider->loadUserByUsername($username);

        if (!$user instanceof UserInterface) {
            throw new \UnexpectedValueException(sprintf(
                'User should be an instance of %s, %s loaded.',
                UserInterface::class,
                get_class($user)
            ));
        }

        return $user;
    }

}
