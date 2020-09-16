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

namespace IdentityAccess\Application\Command\Identity\EnableUser;

use IdentityAccess\Application\Command\Identity\AbstractCommandHandler;

/**
 * Class EnableUserHandler
 *
 * @package IdentityAccess\Application\Command\Identity\EnableUser
 */
final class EnableUserHandler extends AbstractCommandHandler
{
    public function __invoke(EnableUserCommand $command)
    {
        $user = $this->getUser($command->userId);

        $user->enable($command->enabledById);

        $this->storeUser($user);
    }

}
