<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Query\Access;

/**
 * Interface RefreshTokenInterface
 *
 * @package IdentityAccess\Application\Query\Access
 */
interface RefreshTokenInterface
{
    public function __toString();

    public function toString(): string;

    public function getValue(): string;

    public function getUsername();

    public function isValid();

}
