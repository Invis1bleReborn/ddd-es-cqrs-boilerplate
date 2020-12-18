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

namespace Common\Shared\Application\Query\Filter;

/**
 * Interface FilterInterface.
 */
interface FilterInterface extends ModelAwareInterface
{
    public function getValue();

    public static function getPropertyName(): string;

    public static function getType(): Type;

    public static function getMatchingStrategy(): ?MatchingStrategy;
}
