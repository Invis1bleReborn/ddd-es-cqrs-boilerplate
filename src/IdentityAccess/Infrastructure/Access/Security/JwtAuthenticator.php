<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\GuardBridgeAuthenticator;

/**
 * Class JwtAuthenticator
 *
 * @package IdentityAccess\Infrastructure\Access\Security
 */
final class JwtAuthenticator extends GuardBridgeAuthenticator
{
    public function __construct(
        JWTTokenAuthenticator $jwtGuardAuthenticator,
        UserProviderInterface $userProvider
    )
    {
        parent::__construct($jwtGuardAuthenticator, $userProvider);
    }

}
