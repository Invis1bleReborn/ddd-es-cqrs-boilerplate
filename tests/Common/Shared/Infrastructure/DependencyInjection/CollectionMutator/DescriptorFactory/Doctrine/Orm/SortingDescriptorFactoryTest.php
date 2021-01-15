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

use Common\Shared\Application\Query\Sorting\SortingStub;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterDescriptorFactoryTestCase.
 */
class SortingDescriptorFactoryTest extends TestCase
{
    protected SortingDescriptorFactory $descriptorFactory;

    /**
     * @test
     */
    public function itSupportsSorting()
    {
        $this->assertTrue($this->descriptorFactory->supports(SortingStub::class));
    }

    /**
     * @test
     */
    public function itDoesNotSupportNonSorting()
    {
        $this->assertFalse($this->descriptorFactory->supports(__CLASS__));
    }

    protected function setUp(): void
    {
        if (!isset($this->descriptorFactory)) {
            $this->descriptorFactory = new SortingDescriptorFactory();
        }
    }
}
