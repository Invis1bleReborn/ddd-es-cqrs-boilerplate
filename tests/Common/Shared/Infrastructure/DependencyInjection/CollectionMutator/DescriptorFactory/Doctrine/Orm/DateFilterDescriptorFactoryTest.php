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
use Common\Shared\Application\Query\Filter\DateFilterStub;
use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;

/**
 * Class DateFilterDescriptorFactoryTest.
 */
class DateFilterDescriptorFactoryTest extends FilterDescriptorFactoryTestCase
{
    /**
     * @test
     */
    public function itSupportsDateFilter()
    {
        $this->assertTrue($this->descriptorFactory->supports(DateFilterStub::class));
    }

    /**
     * @test
     */
    public function itDoesNotSupportNonDateFilter()
    {
        $this->assertFalse($this->descriptorFactory->supports(BooleanFilterStub::class));
    }

    protected function createDescriptorFactory(): DescriptorFactory\AbstractFilterDescriptorFactory
    {
        return new DateFilterDescriptorFactory();
    }
}
