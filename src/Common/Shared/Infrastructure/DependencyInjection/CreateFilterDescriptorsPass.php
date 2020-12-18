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

use ApiPlatform\Core\Util\ReflectionClassRecursiveIterator;
use Common\Shared\Infrastructure\DependencyInjection\Filter\Handler\FilterHandlerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CreateFilterDescriptorsPass.
 */
class CreateFilterDescriptorsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $filterClasses = ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories(
            $container->getParameter('app.query.filter_directories')
        );

        $descriptors = [];

        foreach ($filterClasses as $filterClass) {
            $filterClassName = $filterClass->getName();

            foreach ($container->findTaggedServiceIds('app.query.filter_handler', true) as $serviceId => $tags) {
                $handler = $container->get($serviceId);
                /* @var $handler FilterHandlerInterface */

                if (!$handler->supports($filterClassName)) {
                    continue;
                }

                $descriptor = $handler->handle($filterClass);

                if (isset($descriptors[$descriptor['id']], $descriptor['arguments']['properties'])) {
                    if (isset($descriptors[$descriptor['id']]['arguments']['properties'])) {
                        $descriptors[$descriptor['id']]['arguments']['properties'] = array_merge(
                            $descriptors[$descriptor['id']]['arguments']['properties'],
                            $descriptor['arguments']['properties']
                        );
                    } else {
                        $descriptors[$descriptor['id']]['arguments']['properties'] =
                            $descriptor['arguments']['properties'];
                    }
                } else {
                    $descriptors[$descriptor['id']] = $descriptor;
                }
            }
        }

        $container->setParameter('app.query.filter_descriptors', $descriptors);
    }

    protected function findFilterHandlers(ContainerBuilder $container): array
    {
        $handlers = [];

        foreach ($container->findTaggedServiceIds('app.query.filter_handler', true) as $serviceId => $tags) {
            $handlers[] = new Reference($serviceId);
        }

        return $handlers;
    }
}
