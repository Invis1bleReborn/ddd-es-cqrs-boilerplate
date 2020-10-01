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

namespace IdentityAccess\Ui\Access;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Infrastructure\Access\Security\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class UserProvider.
 */
class UserProvider implements UserProviderInterface
{
    private \IdentityAccess\Application\Query\Identity\UserProviderInterface $userProvider;

    public function __construct(\IdentityAccess\Application\Query\Identity\UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function load(string $username): UserInterface
    {
        try {
            return $this->userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $exception) {
            throw new BadCredentialsException(null, [], 0, $exception);
        }
    }
}
