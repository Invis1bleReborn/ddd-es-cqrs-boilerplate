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

namespace IdentityAccess\Infrastructure\Identity\AuthenticatedUserProvider;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Identity\AuthenticatedUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AuthenticatedUserProvider
 *
 * @package IdentityAccess\Infrastructure\Identity\AuthenticatedUserProvider
 */
final class AuthenticatedUserProvider implements AuthenticatedUserProviderInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            return null;
        }

        $user = $token->getUser();

        if ($user instanceof UserInterface) {
            return $user;
        }

        return null;
    }
}
