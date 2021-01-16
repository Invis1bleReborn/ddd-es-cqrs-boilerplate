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

use Common\Shared\Application\Query\Filter\UnsupportedFilterStub;

/**
 * Class FilterDescriptorFactoryTestCase.
 */
abstract class FilterDescriptorFactoryTestAbstract extends AbstractMutatorDescriptorFactoryTest
{
    /**
     * @test
     */
    public function itDoesNotSupportInappropriateMutator()
    {
        $this->assertFalse($this->descriptorFactory->supports(UnsupportedFilterStub::class));
    }
}
