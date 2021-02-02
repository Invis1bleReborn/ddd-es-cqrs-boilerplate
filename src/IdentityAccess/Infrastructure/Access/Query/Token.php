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

use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Query\Access\TokenInterface;

/**
 * JSON Web Token.
 *
 * @see https://en.wikipedia.org/wiki/JSON_Web_Token Documentation on Wikipedia.org
 */
class Token implements TokenInterface
{
    /**
     * Access token.
     */
    private string $accessToken;

    /**
     * Refresh token.
     */
    private string $refreshToken;

    private DateTime $refreshTokenDateExpired;

    public function __construct(
        string $accessToken,
        string $refreshToken,
        DateTime $refreshTokenDateExpired
    ) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->refreshTokenDateExpired = $refreshTokenDateExpired;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getRefreshTokenDateExpired(): \DateTimeImmutable
    {
        $dateExpired = $this->refreshTokenDateExpired->toNative();
        /* @var $dateExpired \DateTimeImmutable */

        return $dateExpired;
    }

    public function getId(): string
    {
        return md5($this->accessToken);
    }
}
