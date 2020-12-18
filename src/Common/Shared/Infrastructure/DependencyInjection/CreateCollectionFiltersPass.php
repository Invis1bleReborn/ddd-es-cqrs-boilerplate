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

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * Class CreateCollectionFiltersPass.
 */
class CreateCollectionFiltersPass implements CompilerPassInterface
{
    protected const TAG_FILTER_NAME = 'api_platform.filter';

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getParameter('app.query.filter_descriptors') as $descriptor) {
            $this->createFilterDefinition($container, $descriptor);
        }
    }

    private function createFilterDefinition(ContainerBuilder $container, array $descriptor): void
    {
        if ($container->has($descriptor['id'])) {
            return;
        }

        $filterReflectionClass = $container->getReflectionClass($descriptor['class'], false);

        if (null === $filterReflectionClass) {
            throw new InvalidArgumentException(sprintf(
                'Class "%s" used for service "%s" cannot be found.',
                $descriptor['class'],
                $descriptor['id']
            ));
        }

        if ($container->has($descriptor['class']) &&
            ($parentDefinition = $container->findDefinition($descriptor['class']))->isAbstract()
        ) {
            $definition = new ChildDefinition($parentDefinition->getClass());
        } else {
            $definition = new Definition($filterReflectionClass->getName());
            $definition->setAutoconfigured(true);
        }

        $definition->addTag(static::TAG_FILTER_NAME);
        $definition->setAutowired(true);

        $parameterNames = [];

        $constructorReflectionMethod = $filterReflectionClass->getConstructor();

        if (null !== $constructorReflectionMethod) {
            foreach ($constructorReflectionMethod->getParameters() as $reflectionParameter) {
                $parameterNames[$reflectionParameter->name] = true;
            }
        }

        foreach ($descriptor['arguments'] as $key => $value) {
            if (!isset($parameterNames[$key])) {
                throw new InvalidArgumentException(sprintf(
                    'Class "%s" does not have argument "$%s".',
                    $descriptor['class'],
                    $key
                ));
            }

            $definition->setArgument("$$key", $value);
        }

        $container->setDefinition($descriptor['id'], $definition);
    }
}
