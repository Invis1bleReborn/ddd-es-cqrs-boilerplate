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
 * Class SearchFilter.
 */
abstract class SearchFilter extends Filter
{
    protected static ?Type $type;

    protected static ?MatchingStrategy $matchingStrategy;

    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public static function getType(): Type
    {
        if (!isset(static::$type)) {
            static::$type = new Type('SEARCH');
        }

        return static::$type;
    }

    public static function getMatchingStrategy(): MatchingStrategy
    {
        if (!isset(static::$matchingStrategy)) {
            static::$matchingStrategy = new MatchingStrategy(static::getMatchingStrategyValue());
        }

        return static::$matchingStrategy;
    }

    abstract protected static function getMatchingStrategyValue(): string;
}
