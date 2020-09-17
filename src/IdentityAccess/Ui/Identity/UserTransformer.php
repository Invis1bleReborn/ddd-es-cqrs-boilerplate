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

namespace IdentityAccess\Ui\Identity;

use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Class UserTransformer
 *
 */
class UserTransformer
{
    public function __invoke(UserInterface $user): UserView
    {
        return new UserView(
            $user->getId(),
            $user->getEmail(),
            $user->getRoles(),
            $user->isEnabled(),
            $user->getRegisteredById(),
            $user->getDateRegistered(),
        );
    }
}
