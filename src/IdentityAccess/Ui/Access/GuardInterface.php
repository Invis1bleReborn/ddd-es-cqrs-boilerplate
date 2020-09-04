<?php

declare(strict_types=1);

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
