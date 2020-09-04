<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Command\Access\RefreshToken;

use Assert\AssertionFailedException;
use Common\Shared\Application\Bus\Command\CommandInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\ValueObject\RefreshToken;

/**
 * Class RefreshTokenCommand
 *
 * @package IdentityAccess\Application\Command\Access\RefreshToken
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
