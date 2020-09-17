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

namespace IdentityAccess\Ui\Access\RefreshToken;

use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RefreshTokenRequest
 *
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
