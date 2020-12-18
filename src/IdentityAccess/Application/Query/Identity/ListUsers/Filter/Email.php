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

namespace IdentityAccess\Application\Query\Identity\ListUsers\Filter;

use Common\Shared\Application\Query\Filter\SearchFilter;

/**
 * Class Email.
 */
class Email extends SearchFilter
{
    use UserModelAwareTrait;

    public static function getPropertyName(): string
    {
        return 'email';
    }

    protected static function getMatchingStrategyValue(): string
    {
        return 'PARTIAL';
    }
}
