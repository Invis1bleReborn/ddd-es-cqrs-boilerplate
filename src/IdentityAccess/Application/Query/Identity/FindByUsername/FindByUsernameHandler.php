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

namespace IdentityAccess\Application\Query\Identity\FindByUsername;

use Common\Shared\Application\Query\QueryHandlerInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Application\Query\Identity\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class FindByUsernameHandler.
 */
class FindByUsernameHandler implements QueryHandlerInterface
{
    private UserProviderInterface $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function __invoke(FindByUsernameQuery $query): ?UserInterface
    {
        try {
            return $this->userProvider->loadUserByUsername($query->username->toString());
        } catch (UsernameNotFoundException $exception) {
            return null;
        }
    }
}
