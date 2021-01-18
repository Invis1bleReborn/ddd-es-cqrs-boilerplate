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
use Common\Shared\Application\Query\Filter\SearchFilterStub;
use Common\Shared\Application\Query\FooModel;
use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;

/**
 * Class SearchFilterDescriptorFactoryTest.
 */
class SearchFilterDescriptorFactoryTest extends FilterDescriptorFactoryTestAbstract
{
    protected function createDescriptorFactory(): DescriptorFactory\AbstractFilterDescriptorFactory
    {
        return new SearchFilterDescriptorFactory();
    }

    protected function getSupportedMutatorClassName(): string
    {
        return SearchFilterStub::class;
    }

    protected function getExpectedDescriptor(): array
    {
        return [
            'id' => 'php_' .
                'common_shared_application_query_foo_model_' .
                'api_platform_core_bridge_doctrine_orm_filter_search_filter',
            'class' => SearchFilter::class,
            'model_class' => FooModel::class,
            'arguments' => [
                'properties' => [
                    'searchPropertyName' => 'exact',
                ],
            ],
        ];
    }
}
