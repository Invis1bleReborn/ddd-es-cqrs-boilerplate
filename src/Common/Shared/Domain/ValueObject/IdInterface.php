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

namespace Common\Shared\Domain\ValueObject;

/**
 * Interface IdInterface
 *
 * @package Common\Shared\Domain\ValueObject
 */
interface IdInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

}
