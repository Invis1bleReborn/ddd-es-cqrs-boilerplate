<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Query\Access;

/**
 * Interface RefreshTokenProviderInterface
 *
 * @package IdentityAccess\Application\Query\Access
 */
interface RefreshTokenProviderInterface
{
    /**
     * @param string $refreshToken
     *
     * @return RefreshTokenInterface|null
     * @throws \UnexpectedValueException
     */
    public function loadRefreshTokenByValue(string $refreshToken): ?RefreshTokenInterface;

}
