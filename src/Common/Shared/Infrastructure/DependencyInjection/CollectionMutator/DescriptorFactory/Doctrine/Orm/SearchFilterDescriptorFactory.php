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

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;

/**
 * Class SearchFilterDescriptorFactory.
 */
class SearchFilterDescriptorFactory extends DescriptorFactory\AbstractFilterDescriptorFactory
{
    protected function getSupportedType(): string
    {
        return 'SEARCH';
    }

    protected function getFilterClass(): string
    {
        return SearchFilter::class;
    }
}
