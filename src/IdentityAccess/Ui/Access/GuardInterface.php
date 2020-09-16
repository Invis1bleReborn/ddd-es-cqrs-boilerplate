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

namespace IdentityAccess\Ui\Access;

use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Interface GuardInterface
 *
 * @package IdentityAccess\Ui\Access
 */
interface GuardInterface
{
    /**
     * @param UserInterface $user
     * @param object|null   $subject
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isGranted(UserInterface $user, object $subject = null): bool;

}
