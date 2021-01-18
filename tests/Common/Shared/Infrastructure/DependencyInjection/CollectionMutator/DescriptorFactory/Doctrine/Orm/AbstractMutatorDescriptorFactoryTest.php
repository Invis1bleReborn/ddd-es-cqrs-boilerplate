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

use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory as Df;
use PHPUnit\Framework\TestCase;

/**
 * Class MutatorDescriptorFactoryTestCase.
 */
abstract class AbstractMutatorDescriptorFactoryTest extends TestCase
{
    protected Df\CollectionMutatorDescriptorFactoryInterface $descriptorFactory;

    /**
     * @test
     */
    public function itSupportsAppropriateMutator()
    {
        $this->assertTrue($this->descriptorFactory->supports($this->getSupportedMutatorClassName()));
    }

    /**
     * @test
     */
    public function itDoesNotSupportNonMutator()
    {
        $this->assertFalse($this->descriptorFactory->supports(__CLASS__));
    }

    /**
     * @test
     */
    public function itCreatesMutatorDescriptor(): void
    {
        $this->assertSame(
            $this->getExpectedDescriptor(),
            $this->descriptorFactory->create(new \ReflectionClass($this->getSupportedMutatorClassName()))
        );
    }

    protected function setUp(): void
    {
        if (!isset($this->descriptorFactory)) {
            $this->descriptorFactory = $this->createDescriptorFactory();
        }
    }

    abstract protected function createDescriptorFactory(): Df\CollectionMutatorDescriptorFactoryInterface;

    abstract protected function getSupportedMutatorClassName(): string;

    abstract protected function getExpectedDescriptor(): array;
}
