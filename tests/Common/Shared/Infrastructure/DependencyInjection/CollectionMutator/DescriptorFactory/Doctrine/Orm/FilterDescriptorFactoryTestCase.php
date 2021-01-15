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

use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterDescriptorFactoryTestCase.
 */
abstract class FilterDescriptorFactoryTestCase extends TestCase
{
    protected DescriptorFactory\AbstractFilterDescriptorFactory $descriptorFactory;

    /**
     * @test
     */
    public function itDoesNotSupportNonFilter()
    {
        $this->assertFalse($this->descriptorFactory->supports(__CLASS__));
    }

    protected function setUp(): void
    {
        if (!isset($this->descriptorFactory)) {
            $this->descriptorFactory = $this->createDescriptorFactory();
        }
    }

    abstract protected function createDescriptorFactory(): DescriptorFactory\AbstractFilterDescriptorFactory;
}
