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

namespace IdentityAccess\Infrastructure\Identity\Command\DisableUser;

use Broadway\CommandHandling\SimpleCommandHandler;
use IdentityAccess\Application\Command\Identity\DisableUser\DisableUserCommand;
use IdentityAccess\Application\Command\Identity\DisableUser\DisableUserHandler;

/**
 * Class DisableUserHandlerAdapter.
 */
class DisableUserHandlerAdapter extends SimpleCommandHandler
{
    private DisableUserHandler $handler;

    public function __construct(DisableUserHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handleDisableUserCommand(DisableUserCommand $command): void
    {
        ($this->handler)($command);
    }
}
