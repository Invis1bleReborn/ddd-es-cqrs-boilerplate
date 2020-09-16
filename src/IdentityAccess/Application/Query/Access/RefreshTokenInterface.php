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
