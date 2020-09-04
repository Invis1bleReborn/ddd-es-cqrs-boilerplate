<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access\RefreshToken;

use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RefreshTokenRequest
 *
 * @package IdentityAccess\Ui\Access\RefreshToken
 */
final class RefreshTokenRequest implements RequestInterface
{
    /**
     * @Assert\NotBlank
     */
    public ?string $refreshToken;

    public function __construct(?string $refreshToken = null)
    {
        $this->refreshToken = $refreshToken;
    }

}
