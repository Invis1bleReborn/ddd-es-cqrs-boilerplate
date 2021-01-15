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

namespace Common\Shared\Infrastructure\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CreateCollectionMutatorDescriptorsPassTest.
 */
class CreateCollectionMutatorDescriptorsPassTest extends TestCase
{
    public function testProcess(): void
    {
        $container = new ContainerBuilder();

        $container->setParameter('app.query.collection_mutator_directories', [__DIR__]);

        (new CreateCollectionMutatorDescriptorsPass())->process($container);

        $this->assertTrue($container->hasParameter('app.query.collection_mutator_descriptors'));
        $this->assertIsArray($container->getParameter('app.query.collection_mutator_descriptors'));
    }
}
