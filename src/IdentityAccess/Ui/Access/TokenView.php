<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

/**
 * Class TokenView
 *
 * @package IdentityAccess\Ui\Access
 */
final class TokenView
{
    public string $accessToken;

    public string $refreshToken;

    public function __construct(
        string $accessToken,
        string $refreshToken
    )
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

}
