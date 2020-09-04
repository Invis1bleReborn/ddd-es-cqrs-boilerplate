<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Command\Access\CreateToken;

use Assert\AssertionFailedException;
use Common\Shared\Application\Bus\Command\CommandInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;

/**
 * Class CreateTokenCommand
 *
 * @package IdentityAccess\Application\Command\Access\CreateToken
 */
class CreateTokenCommand implements CommandInterface
{
    public UserInterface $user;

    public PlainPassword $plainPassword;

    /**
     * CreateTokenCommand constructor.
     *
     * @param UserInterface $user
     * @param string        $plainPassword
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        UserInterface $user,
        string $plainPassword
    )
    {
        $this->user = $user;
        $this->plainPassword = PlainPassword::fromString($plainPassword);
    }

}
