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

use MyCLabs\Enum\Enum;

/**
 * Class NullsComparisonStrategy.
 */
class NullsComparisonStrategy extends Enum
{
    private const DBMS_DEFAULT = null;

    private const NULLS_SMALLEST = 'NULLS_SMALLEST';

    private const NULLS_LARGEST = 'NULLS_LARGEST';
}
