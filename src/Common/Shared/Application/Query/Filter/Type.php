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

use MyCLabs\Enum\Enum;

/**
 * Class Type.
 */
class Type extends Enum
{
    public const BOOLEAN = 'BOOLEAN';

    private const DATE = 'DATE';

    private const EXISTS = 'EXISTS';

    private const NUMERIC = 'NUMERIC';

    private const RANGE = 'RANGE';

    private const SEARCH = 'SEARCH';
}
