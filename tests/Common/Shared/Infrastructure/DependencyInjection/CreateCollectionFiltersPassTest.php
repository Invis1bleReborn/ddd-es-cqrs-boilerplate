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

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CreateCollectionFiltersPassTest.
 */
class CreateCollectionFiltersPassTest extends TestCase
{
    /**
     * @test
     * @dataProvider validFilterDescriptorDataProvider
     */
    public function itCreatesFilterDefinitions(string $id, string $class, array $arguments): void
    {
        $container = new ContainerBuilder();

        $container->setParameter('app.query.collection_mutator_descriptors', [
            ['id' => $id, 'class' => $class, 'arguments' => $arguments],
        ]);

        $this->process($container);

        $this->assertTrue($container->hasDefinition($id), sprintf('Definition "%s" not found.', $id));

        $definition = $container->getDefinition($id);
        $definitionClass = $definition->getClass();

        $this->assertSame(
            $class,
            $definitionClass,
            sprintf('Definition "%s" class must be "%s", but "%s" found.', $id, $class, $definitionClass)
        );

        $arguments_ = [];

        foreach ($arguments as $key => $value) {
            $arguments_["$$key"] = $value;
        }

        $this->assertSame($arguments_, $definition->getArguments(), 'Unexpected definition arguments.');
    }

    public function validFilterDescriptorDataProvider(): array
    {
        return [
            [
                'search_filter',
                SearchFilter::class,
                ['properties' => []],
            ],
            [
                'date_filter',
                DateFilter::class,
                ['properties' => []],
            ],
            [
                'boolean_filter',
                BooleanFilter::class,
                ['properties' => []],
            ],
            [
                'order_filter',
                OrderFilter::class,
                ['properties' => []],
            ],
        ];
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenMutatorClassNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $container = new ContainerBuilder();

        $descriptors = [
            [
                'id' => 'non_existing',
                'class' => 'NonExisting',
            ],
        ];

        $container->setParameter('app.query.collection_mutator_descriptors', $descriptors);

        $this->process($container);
    }

    protected function process(ContainerBuilder $container): void
    {
        (new CreateCollectionFiltersPass())
            ->process($container);
    }
}
