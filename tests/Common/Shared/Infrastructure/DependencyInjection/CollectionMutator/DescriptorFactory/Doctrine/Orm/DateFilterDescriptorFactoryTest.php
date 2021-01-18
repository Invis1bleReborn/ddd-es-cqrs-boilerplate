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
use Common\Shared\Application\Query\Filter\DateFilterStub;
use Common\Shared\Application\Query\FooModel;
use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;

/**
 * Class DateFilterDescriptorFactoryTest.
 */
class DateFilterDescriptorFactoryTest extends FilterDescriptorFactoryTestAbstract
{
    protected function createDescriptorFactory(): DescriptorFactory\AbstractFilterDescriptorFactory
    {
        return new DateFilterDescriptorFactory();
    }

    protected function getSupportedMutatorClassName(): string
    {
        return DateFilterStub::class;
    }

    protected function getExpectedDescriptor(): array
    {
        return [
            'id' => 'php_' .
                'common_shared_application_query_foo_model_' .
                'api_platform_core_bridge_doctrine_orm_filter_date_filter',
            'class' => DateFilter::class,
            'model_class' => FooModel::class,
            'arguments' => [
                'properties' => [
                    'datePropertyName' => null,
                ],
            ],
        ];
    }
}
