<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Command\Identity\DisableUser;

use IdentityAccess\Application\Command\Identity\AbstractCommandHandler;

/**
 * Class DisableUserHandler
 *
 * @package IdentityAccess\Application\Command\Identity\DisableUser
 */
final class DisableUserHandler extends AbstractCommandHandler
{
    public function __invoke(DisableUserCommand $command)
    {
        $user = $this->getUser($command->userId);

        $user->disable($command->disabledById);

        $this->storeUser($user);
    }

}
