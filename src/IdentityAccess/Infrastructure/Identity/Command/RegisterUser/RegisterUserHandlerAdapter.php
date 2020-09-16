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

namespace IdentityAccess\Infrastructure\Identity\Command\RegisterUser;

use Broadway\CommandHandling\SimpleCommandHandler;
use IdentityAccess\Application\Command\Identity\RegisterUser\RegisterUserCommand;
use IdentityAccess\Application\Command\Identity\RegisterUser\RegisterUserHandler;

/**
 * Class RegisterUserHandlerAdapter
 *
 * @package IdentityAccess\Infrastructure\Identity\Command\RegisterUser
 */
class RegisterUserHandlerAdapter extends SimpleCommandHandler
{
    private RegisterUserHandler $handler;

    public function __construct(RegisterUserHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handleRegisterUserCommand(RegisterUserCommand $command): void
    {
        ($this->handler)($command);
    }

}
