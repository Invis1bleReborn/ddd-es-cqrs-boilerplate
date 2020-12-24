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

namespace IdentityAccess\Application\Query\Identity\ListUsers\Sorting;

use Common\Shared\Application\Query\Sorting\DefaultNullsComparisonStrategyAwareSorting;
use IdentityAccess\Application\Query\Identity\ListUsers\UserModelAwareTrait;

/**
 * Class UserModelAwareSorting.
 */
abstract class UserModelAwareSorting extends DefaultNullsComparisonStrategyAwareSorting
{
    use UserModelAwareTrait;
}
