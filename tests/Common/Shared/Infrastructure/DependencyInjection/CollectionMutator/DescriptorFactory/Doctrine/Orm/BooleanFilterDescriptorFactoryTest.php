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

use Common\Shared\Application\Query\Filter\BooleanFilterStub;
use Common\Shared\Application\Query\Filter\SearchFilterStub;
use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;

/**
 * Class BooleanFilterDescriptorFactoryTest.
 */
class BooleanFilterDescriptorFactoryTest extends FilterDescriptorFactoryTestCase
{
    /**
     * @test
     */
    public function itSupportsBooleanFilter()
    {
        $this->assertTrue($this->descriptorFactory->supports(BooleanFilterStub::class));
    }

    /**
     * @test
     */
    public function itDoesNotSupportNonBooleanFilter()
    {
        $this->assertFalse($this->descriptorFactory->supports(SearchFilterStub::class));
    }

    protected function createDescriptorFactory(): DescriptorFactory\AbstractFilterDescriptorFactory
    {
        return new BooleanFilterDescriptorFactory();
    }
}
