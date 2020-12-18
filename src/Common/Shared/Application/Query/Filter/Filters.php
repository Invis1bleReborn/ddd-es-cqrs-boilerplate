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

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * Class Filters.
 */
class Filters implements \IteratorAggregate
{
    private iterable $filters;

    /**
     * Filters constructor.
     *
     * @param iterable<Filter> $filters
     *
     * @throws AssertionFailedException
     */
    public function __construct(iterable $filters)
    {
        foreach ($filters as $filter) {
            Assertion::isInstanceOf($filter, Filter::class);
        }

        $this->filters = $filters;
    }

    public function getIterator()
    {
        yield from $this->filters;
    }
}
