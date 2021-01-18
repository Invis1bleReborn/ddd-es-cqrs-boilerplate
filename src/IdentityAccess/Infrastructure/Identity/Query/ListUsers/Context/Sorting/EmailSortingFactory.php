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

namespace IdentityAccess\Infrastructure\Identity\Query\ListUsers\Context\Sorting;

use Common\Shared\Application\Query\Sorting\Direction;
use Common\Shared\Application\Query\Sorting\SortingInterface;
use IdentityAccess\Application\Query\Identity\ListUsers\Sorting\Email;

/**
 * Class EmailSortingFactory.
 */
class EmailSortingFactory extends BaseSortingFactory
{
    protected function getSupportedSortingClass(): string
    {
        return Email::class;
    }

    protected function getSupportedContextKey(): string
    {
        return 'email';
    }

    protected function createSortingFromDirection(Direction $direction): SortingInterface
    {
        return new Email($direction);
    }
}
