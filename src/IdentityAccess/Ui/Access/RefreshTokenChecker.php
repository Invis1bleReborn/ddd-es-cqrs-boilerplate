<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

use IdentityAccess\Application\Query\Access\RefreshTokenInterface;
use IdentityAccess\Infrastructure\Access\Security\AuthenticationException;

/**
 * Class RefreshTokenChecker
 *
 * @package IdentityAccess\Ui\Access
 */
class RefreshTokenChecker implements RefreshTokenCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(?RefreshTokenInterface $refreshToken): void
    {
        if (null === $refreshToken) {
            throw new AuthenticationException('Refresh token does not exist.');
        }

        if (!$refreshToken->isValid()) {
            throw new AuthenticationException(sprintf('Refresh token "%s" is invalid.', $refreshToken));
        }
    }

}
