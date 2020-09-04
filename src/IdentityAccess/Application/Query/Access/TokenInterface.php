<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Query\Access;

/**
 * Interface TokenInterface
 *
 * @package IdentityAccess\Application\Query\Access
 */
interface TokenInterface
{
    public function getAccessToken(): string;

    public function getRefreshToken(): string;

    public function getRefreshTokenDateExpired(): \DateTimeImmutable;

}
