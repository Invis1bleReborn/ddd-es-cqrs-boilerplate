<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IdentityAccess\Ui\Identity\RegisterUser;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\GuardInterface;

/**
 * Class RegisterUserGuard
 *
 * @package IdentityAccess\Ui\Identity\RegisterUser
 */
class RegisterUserGuard implements GuardInterface
{
    /**
     * {@inheritdoc}
     */
    public function isGranted(UserInterface $user, object $subject = null): bool
    {
        return true;
    }

}
