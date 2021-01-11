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

namespace IdentityAccess\Infrastructure\Identity\Query\ListUsers\Context\Filter;

use Common\Shared\Application\Query\Filter\FilterInterface;
use IdentityAccess\Application\Query\Identity\ListUsers\Filter\Email;

/**
 * Class EmailFilterFactory.
 */
class EmailFilterFactory extends BaseFilterFactory
{
    protected function getSupportedFilterClass(): string
    {
        return Email::class;
    }

    protected function getSupportedContextKey(): string
    {
        return 'email';
    }

    /**
     * @param FilterInterface $filter
     */
    protected function createContextValue($filter): string
    {
        return $filter->getValue();
    }

    protected function createFilterFromContextValue($value): FilterInterface
    {
        return new Email($value);
    }
}
