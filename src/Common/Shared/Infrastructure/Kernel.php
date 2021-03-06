<?php

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common\Shared\Infrastructure;

use Broadway\ReadModel\Repository;
use Common\Shared\Infrastructure\DependencyInjection\CreateCollectionFiltersPass;
use Common\Shared\Infrastructure\DependencyInjection\CreateCollectionMutatorDescriptorsPass;
use Common\Shared\Infrastructure\DependencyInjection\RegisterMessageHandlersPass;
use IdentityAccess\Infrastructure\Identity\Query\Orm\OrmUserReadModelRepository;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\TypedReference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Class Kernel.
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $container->parameters()
            ->set('container.dumper.inline_class_loader', \PHP_VERSION_ID < 70400 || $this->debug)
            ->set('container.dumper.inline_factories', true)
        ;

        $container->import($confDir . '/{packages}/*' . self::CONFIG_EXTS);
        $container->import($confDir . '/{packages}/' . $this->environment . '/*' . self::CONFIG_EXTS);
        $container->import($confDir . '/{services}' . self::CONFIG_EXTS);
        $container->import($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS);

        // TODO: refactor services registering
        $this->registerServicesWithWellKnownNames($container);
    }

    protected function registerServicesWithWellKnownNames(ContainerConfigurator $container): void
    {
        $services = $container->services();

        $services->defaults()
            ->bind(
                Repository::class . ' $userRepository',
                new TypedReference(OrmUserReadModelRepository::class, OrmUserReadModelRepository::class)
            );

        $servicePatterns = [
            'Infrastructure/**/GuardAdapter/*GuardAdapter',
            'Infrastructure/**/Query/**/*Repository',
            'Infrastructure/**/Query/*Projector',
            'Infrastructure/**/Query/*Provider',
            'Infrastructure/**/Repository/*Store',
            'Infrastructure/**/Request/*RequestTransformerAdapter',
            'Infrastructure/**/Specification/*Specification',
            'Infrastructure/**/View/*TransformerAdapter',
            'Ui/**/*Command',
            'Ui/**/*Guard',
            'Ui/**/*Transformer',
        ];

        $servicesPattern = '{' . implode(',', $servicePatterns) . '}.php';

        foreach ([
            'IdentityAccess\\',
            'Common\\Shared\\',
        ] as $namespacePrefix) {
            $services->load($namespacePrefix, sprintf(
                '%s/src/%s%s',
                $this->getProjectDir(),
                strtr($namespacePrefix, '\\', '/'),
                $servicesPattern
            ))
                ->autowire(true)
                ->autoconfigure(true)
            ;
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/' . $this->environment . '/*' . self::CONFIG_EXTS);
        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS);
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS);
    }

    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterMessageHandlersPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 99999);
        $container->addCompilerPass(
            new CreateCollectionMutatorDescriptorsPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            11
        );
        $container->addCompilerPass(new CreateCollectionFiltersPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }
}
