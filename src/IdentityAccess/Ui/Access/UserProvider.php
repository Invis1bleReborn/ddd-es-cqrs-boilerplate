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

use Common\Shared\Application\Query\QueryBusInterface;
use IdentityAccess\Application\Query\Identity\FindByUsername\FindByUsernameQuery;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Infrastructure\Access\Security\BadCredentialsException;

/**
 * Class UserProvider.
 */
class UserProvider implements UserProviderInterface
{
    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function load(string $username): UserInterface
    {
        $user = $this->queryBus->ask(new FindByUsernameQuery($username));

        if (null === $user) {
            throw new BadCredentialsException();
        }

        return $user;
    }
}
