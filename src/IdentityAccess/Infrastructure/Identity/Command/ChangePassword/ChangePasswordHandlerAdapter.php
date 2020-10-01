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

namespace IdentityAccess\Infrastructure\Identity\Command\ChangePassword;

use Broadway\CommandHandling\SimpleCommandHandler;
use IdentityAccess\Application\Command\Identity\ChangePassword\ChangePasswordCommand;
use IdentityAccess\Application\Command\Identity\ChangePassword\ChangePasswordHandler;

/**
 * Class ChangePasswordHandlerAdapter.
 */
class ChangePasswordHandlerAdapter extends SimpleCommandHandler
{
    private ChangePasswordHandler $handler;

    public function __construct(ChangePasswordHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handleChangePasswordCommand(ChangePasswordCommand $command): void
    {
        ($this->handler)($command);
    }
}
