<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Query\Identity;

/**
 * Interface EnableableUserInterface
 *
 * @package IdentityAccess\Application\Query\Identity
 */
interface EnableableUserInterface
{
    public function isEnabled(): bool;

}
