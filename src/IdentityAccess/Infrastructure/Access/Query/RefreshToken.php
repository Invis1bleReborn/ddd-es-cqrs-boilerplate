<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Query;

use Gesdinet\JWTRefreshTokenBundle\Entity;
use IdentityAccess\Application\Query\Access\RefreshTokenInterface;

/**
 * RefreshToken
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
