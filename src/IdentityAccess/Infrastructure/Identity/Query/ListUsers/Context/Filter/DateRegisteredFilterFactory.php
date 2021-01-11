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
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Query\Identity\ListUsers\Filter\DateRegistered;

/**
 * Class DateRegisteredFilterFactory.
 */
class DateRegisteredFilterFactory extends BaseFilterFactory
{
    protected function getSupportedFilterClass(): string
    {
        return DateRegistered::class;
    }

    protected function getSupportedContextKey(): string
    {
        return 'dateRegistered';
    }

    /**
     * @param FilterInterface $filter
     */
    protected function createContextValue($filter): array
    {
        $value = [];

        foreach ($filter->getValue() as $key => $dateTime) {
            /* @var $dateTime DateTime */
            $value[$key] = $dateTime->toString();
        }

        return $value;
    }

    protected function createFilterFromContextValue($value): FilterInterface
    {
        $transformed = [];

        foreach ($value as $key => $dateTime) {
            $transformed[$key] = DateTime::fromString($dateTime);
        }

        return new DateRegistered($transformed);
    }
}
