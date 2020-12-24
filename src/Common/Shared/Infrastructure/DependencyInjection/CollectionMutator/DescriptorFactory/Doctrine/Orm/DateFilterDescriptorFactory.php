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

namespace Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory\Doctrine\Orm;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory\
    AbstractFilterDescriptorFactory;

/**
 * Class DateFilterDescriptorFactory.
 */
class DateFilterDescriptorFactory extends AbstractFilterDescriptorFactory
{
    protected function getSupportedType(): string
    {
        return 'DATE';
    }

    protected function getFilterClass(): string
    {
        return DateFilter::class;
    }
}
