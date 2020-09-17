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

namespace IdentityAccess\Application\Command\Access\CreateToken;

use Assert\AssertionFailedException;
use Common\Shared\Application\Bus\Command\CommandInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;

/**
 * Class CreateTokenCommand
 */
class CreateTokenCommand implements CommandInterface
{
    public UserInterface $user;

    public PlainPassword $plainPassword;

    /**
     * CreateTokenCommand constructor.
     *
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
