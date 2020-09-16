<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
