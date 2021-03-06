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

use Gesdinet\JWTRefreshTokenBundle\Entity;
use IdentityAccess\Application\Query\Access\RefreshTokenInterface;

/**
 * RefreshToken.
 */
class RefreshToken extends Entity\RefreshToken implements RefreshTokenInterface
{
    public function toString(): string
    {
        return $this->getRefreshToken();
    }

    public function getValue(): string
    {
        return $this->getRefreshToken();
    }
}
