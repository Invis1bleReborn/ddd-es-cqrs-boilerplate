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
 * Class BooleanFilter.
 */
abstract class BooleanFilter extends Filter
{
    protected static ?Type $type;

    public function __construct(bool $value)
    {
        parent::__construct($value);
    }

    public static function getType(): Type
    {
        if (!isset(static::$type)) {
            static::$type = new Type('BOOLEAN');
        }

        return self::$type;
    }

    public static function getMatchingStrategy(): ?MatchingStrategy
    {
        return null;
    }
}
