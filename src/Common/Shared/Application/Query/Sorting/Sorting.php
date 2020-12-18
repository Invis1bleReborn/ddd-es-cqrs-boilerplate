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

namespace Common\Shared\Application\Query\Sorting;

/**
 * Class Sorting.
 */
class Sorting
{
    public string $propertyName;

    public Direction $direction;

    public ?NullsComparisonStrategy $nullsComparisonStrategy;

    public function __construct(
        string $propertyName,
        Direction $direction,
        NullsComparisonStrategy $nullsComparisonStrategy = null
    ) {
        $this->propertyName = $propertyName;
        $this->direction = $direction;
        $this->nullsComparisonStrategy = $nullsComparisonStrategy;
    }
}
