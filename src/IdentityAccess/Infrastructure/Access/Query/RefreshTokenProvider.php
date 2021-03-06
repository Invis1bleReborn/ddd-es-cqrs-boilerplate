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

namespace IdentityAccess\Infrastructure\Access\Query;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use IdentityAccess\Application\Query\Access\RefreshTokenInterface;
use IdentityAccess\Application\Query\Access\RefreshTokenProviderInterface;

/**
 * Class RefreshTokenProvider.
 */
final class RefreshTokenProvider implements RefreshTokenProviderInterface
{
    private RefreshTokenManagerInterface $refreshTokenManager;

    public function __construct(RefreshTokenManagerInterface $refreshTokenManager)
    {
        $this->refreshTokenManager = $refreshTokenManager;
    }

    public function loadRefreshTokenByValue(string $value): ?RefreshTokenInterface
    {
        $refreshToken = $this->refreshTokenManager->get($value);

        if (null === $refreshToken) {
            return null;
        }

        if (!$refreshToken instanceof RefreshTokenInterface) {
            throw new \UnexpectedValueException(sprintf(
                'Refresh Token should be an instance of %s, %s loaded.',
                RefreshTokenInterface::class,
                get_class($refreshToken)
            ));
        }

        return $refreshToken;
    }
}
