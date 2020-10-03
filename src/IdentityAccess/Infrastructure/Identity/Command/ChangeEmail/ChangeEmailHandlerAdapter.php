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

namespace IdentityAccess\Infrastructure\Identity\Command\ChangeEmail;

use Broadway\CommandHandling\SimpleCommandHandler;
use IdentityAccess\Application\Command\Identity\ChangeEmail\ChangeEmailCommand;
use IdentityAccess\Application\Command\Identity\ChangeEmail\ChangeEmailHandler;

/**
 * Class ChangeEmailHandlerAdapter.
 */
class ChangeEmailHandlerAdapter extends SimpleCommandHandler
{
    private ChangeEmailHandler $handler;

    public function __construct(ChangeEmailHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handleChangeEmailCommand(ChangeEmailCommand $command): void
    {
        ($this->handler)($command);
    }
}
