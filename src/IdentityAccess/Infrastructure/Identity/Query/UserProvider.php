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
