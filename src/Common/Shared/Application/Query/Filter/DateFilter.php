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

use Common\Shared\Domain\ValueObject\DateTime;

/**
 * Class DateFilter.
 */
abstract class DateFilter extends Filter
{
    protected static ?Type $type;

    /**
     * DateFilter constructor.
     *
     * @param array<string, DateTime> $value
     */
    public function __construct(array $value)
    {
        parent::__construct($value);
    }

    public static function getType(): Type
    {
        if (!isset(static::$type)) {
            static::$type = new Type('DATE');
        }

        return self::$type;
    }

    public static function getMatchingStrategy(): ?MatchingStrategy
    {
        return null;
    }

    /**
     * @return array<string, DateTime> $value
     */
    public function getValue(): array
    {
        return parent::getValue();
    }
}
