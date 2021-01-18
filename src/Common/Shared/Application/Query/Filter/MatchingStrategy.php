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
 * Class MatchingStrategy.
 */
class MatchingStrategy extends Enum
{
    private const EXACT = 'EXACT';

    private const PARTIAL = 'PARTIAL';

    private const START = 'START';

    private const END = 'END';

    private const WORD_START = 'WORD_START';
}
