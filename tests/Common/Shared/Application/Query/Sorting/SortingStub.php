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

use Common\Shared\Application\Query\FooModelAwareTrait;

/**
 * Class SortingStub.
 */
class SortingStub extends Sorting
{
    use FooModelAwareTrait;

    public static function getPropertyName(): string
    {
        return 'sortingPropertyName';
    }

    public static function getNullsComparisonStrategy(): ?NullsComparisonStrategy
    {
        return new NullsComparisonStrategy(null);
    }
}
