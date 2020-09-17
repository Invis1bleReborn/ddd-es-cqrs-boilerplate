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
