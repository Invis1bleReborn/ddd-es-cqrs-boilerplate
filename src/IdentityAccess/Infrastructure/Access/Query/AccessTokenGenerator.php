<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IdentityAccess\Infrastructure\Access\Query;

use IdentityAccess\Application\Query\Access\AccessTokenGeneratorInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\ValueObject\AccessToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * Class AccessTokenGenerator
 *
 * @package IdentityAccess\Infrastructure\Access\Query
 */
final class AccessTokenGenerator implements AccessTokenGeneratorInterface
{
    private JWTTokenManagerInterface $tokenManager;

    public function __construct(JWTTokenManagerInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function __invoke(UserInterface $user): AccessToken
    {
        if (!$user instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            throw new \InvalidArgumentException(sprintf(
                'User should be an instance of %s, %s given.',
                \Symfony\Component\Security\Core\User\UserInterface::class,
                get_class($user)
            ));
        }

        return AccessToken::fromString($this->tokenManager->create($user));
    }

}
