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

namespace IdentityAccess\Application\Command\Access\ChangeRoles;

use IdentityAccess\Application\Command\Identity\AbstractCommandHandler;

/**
 * Class ChangeRolesHandler.
 */
final class ChangeRolesHandler extends AbstractCommandHandler
{
    public function __invoke(ChangeRolesCommand $command)
    {
        $user = $this->getUser($command->userId);

        $user->changeRoles(
            $command->roles,
            $command->changedById
        );

        $this->storeUser($user);
    }
}
