<?php

declare(strict_types=1);

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
