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
use Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CreateCollectionMutatorDescriptorsPass.
 */
class CreateCollectionMutatorDescriptorsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $mutatorClasses = ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories(
            $container->getParameter('app.query.collection_mutator_directories')
        );

        $descriptorFactories = $container->findTaggedServiceIds(
            'app.query.collection_mutator_descriptor_factory',
            true
        );

        $descriptors = [];

        foreach ($mutatorClasses as $mutatorClass) {
            if (!$mutatorClass->isInstantiable()) {
                continue;
            }

            $mutatorClassName = $mutatorClass->getName();

            foreach ($descriptorFactories as $serviceId => $tags) {
                $factory = $container->get($serviceId);
                /* @var $factory DescriptorFactory\CollectionMutatorDescriptorFactoryInterface */

                if (!$factory->supports($mutatorClassName)) {
                    continue;
                }

                $descriptor = $factory->create($mutatorClass);

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

        $container->setParameter('app.query.collection_mutator_descriptors', $descriptors);
    }
}
