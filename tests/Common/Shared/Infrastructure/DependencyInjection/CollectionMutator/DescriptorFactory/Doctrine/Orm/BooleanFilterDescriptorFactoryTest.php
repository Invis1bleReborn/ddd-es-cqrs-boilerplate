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

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Common\Shared\Application\Query\Filter\BooleanFilterStub;
use Common\Shared\Application\Query\FooModel;
use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;

/**
 * Class BooleanFilterDescriptorFactoryTest.
 */
class BooleanFilterDescriptorFactoryTest extends FilterDescriptorFactoryTestAbstract
{
    protected function createDescriptorFactory(): DescriptorFactory\AbstractFilterDescriptorFactory
    {
        return new BooleanFilterDescriptorFactory();
    }

    protected function getSupportedMutatorClassName(): string
    {
        return BooleanFilterStub::class;
    }

    protected function getExpectedDescriptor(): array
    {
        return [
            'id' => 'php_' .
                'common_shared_application_query_foo_model_' .
                'api_platform_core_bridge_doctrine_orm_filter_boolean_filter',
            'class' => BooleanFilter::class,
            'model_class' => FooModel::class,
            'arguments' => [
                'properties' => [
                    'booleanPropertyName' => null,
                ],
            ],
        ];
    }
}
