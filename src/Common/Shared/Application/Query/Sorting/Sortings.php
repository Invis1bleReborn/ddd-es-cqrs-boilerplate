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

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * Class Sortings.
 */
class Sortings implements \IteratorAggregate
{
    private iterable $sortings;

    /**
     * Sortings constructor.
     *
     * @param iterable<Sorting> $sortings
     *
     * @throws AssertionFailedException
     */
    public function __construct(iterable $sortings)
    {
        foreach ($sortings as $sorting) {
            Assertion::isInstanceOf($sorting, Sorting::class);
        }

        $this->sortings = $sortings;
    }

    public function getIterator()
    {
        yield from $this->sortings;
    }
}
