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

namespace IdentityAccess\Infrastructure\Identity\Command\EnableUser;

use Broadway\CommandHandling\SimpleCommandHandler;
use IdentityAccess\Application\Command\Identity\EnableUser\EnableUserCommand;
use IdentityAccess\Application\Command\Identity\EnableUser\EnableUserHandler;

/**
 * Class EnableUserHandlerAdapter.
 */
class EnableUserHandlerAdapter extends SimpleCommandHandler
{
    private EnableUserHandler $handler;

    public function __construct(EnableUserHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handleEnableUserCommand(EnableUserCommand $command): void
    {
        ($this->handler)($command);
    }
}
