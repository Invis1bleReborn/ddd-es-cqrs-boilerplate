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

namespace IdentityAccess\Application\Command\Access\RefreshToken;

use Assert\AssertionFailedException;
use Common\Shared\Application\Bus\Command\CommandInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\ValueObject\RefreshToken;

/**
 * Class RefreshTokenCommand
 *
 */
class RefreshTokenCommand implements CommandInterface
{
    public UserInterface $user;

    public RefreshToken $refreshToken;

    /**
     * RefreshTokenCommand constructor.
     *
     * @param UserInterface $user
     * @param string        $refreshToken
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        UserInterface $user,
        string $refreshToken
    )
    {
        $this->user = $user;
        $this->refreshToken = RefreshToken::fromString($refreshToken);
    }
}
