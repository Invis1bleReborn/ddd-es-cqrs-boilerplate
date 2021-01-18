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

namespace Common\Shared\Application\Query;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Common\Shared\Application\Query\Filter\Filters;
use Common\Shared\Application\Query\Sorting\Sortings;

/**
 * Class AbstractListQuery.
 */
abstract class AbstractListQuery implements ListQueryInterface
{
    public ?Filters $filters;

    public ?Sortings $sortings;

    public ?int $limit;

    public ?int $offset;

    /**
     * AbstractListQuery constructor.
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        Filters $filters = null,
        Sortings $sortings = null,
        int $limit = null,
        int $offset = null
    ) {
        Assertion::nullOrGreaterOrEqualThan($limit, 0);

        $this->filters = $filters;
        $this->sortings = $sortings;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return static
     * @throws AssertionFailedException
     */
    public static function create(
        Filters $filters = null,
        Sortings $sortings = null,
        int $limit = null,
        int $offset = null
    )
    {
        return new static($filters, $sortings, $limit, $offset);
    }
    
    public function getFilters(): ?Filters
    {
        return $this->filters;
    }
    
    public function getSortings(): ?Sortings
    {
        return $this->sortings;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
