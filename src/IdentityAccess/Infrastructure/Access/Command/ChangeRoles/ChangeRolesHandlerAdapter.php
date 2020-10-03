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

namespace IdentityAccess\Infrastructure\Access\Command\ChangeRoles;

use Broadway\CommandHandling\SimpleCommandHandler;
use IdentityAccess\Application\Command\Access\ChangeRoles\ChangeRolesCommand;
use IdentityAccess\Application\Command\Access\ChangeRoles\ChangeRolesHandler;

/**
 * Class ChangeRolesHandlerAdapter.
 */
class ChangeRolesHandlerAdapter extends SimpleCommandHandler
{
    private ChangeRolesHandler $handler;

    public function __construct(ChangeRolesHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handleChangeRolesCommand(ChangeRolesCommand $command): void
    {
        ($this->handler)($command);
    }
}
