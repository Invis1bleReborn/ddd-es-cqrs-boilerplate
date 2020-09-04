<?php

declare(strict_types=1);

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
