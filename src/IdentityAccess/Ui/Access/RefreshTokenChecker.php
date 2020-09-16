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
