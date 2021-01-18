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

use Common\Shared\Application\Query\Filter\Filters;
use Common\Shared\Application\Query\Sorting\Sortings;

/**
 * Interface ListQueryInterface.
 */
interface ListQueryInterface extends QueryInterface
{
    /**
     * @return $this
     */
    public static function create(
        Filters $filters = null,
        Sortings $sortings = null,
        int $limit = null,
        int $offset = null
    );

    public function getFilters(): ?Filters;

    public function getSortings(): ?Sortings;

    public function getLimit(): ?int;

    public function getOffset(): ?int;
}
