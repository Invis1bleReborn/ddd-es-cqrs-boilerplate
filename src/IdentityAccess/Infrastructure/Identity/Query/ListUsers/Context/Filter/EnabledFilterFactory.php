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
use IdentityAccess\Application\Query\Identity\ListUsers\Filter\Enabled;

/**
 * Class EnabledFilterFactory.
 */
class EnabledFilterFactory extends BaseFilterFactory
{
    protected function getSupportedFilterClass(): string
    {
        return Enabled::class;
    }

    protected function getSupportedContextKey(): string
    {
        return 'enabled';
    }

    /**
     * @param FilterInterface $filter
     */
    protected function createContextValue($filter): bool
    {
        return $filter->getValue();
    }

    protected function createFilterFromContextValue($value): FilterInterface
    {
        return new Enabled($value);
    }
}
