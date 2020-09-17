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

use Common\Shared\Infrastructure\DependencyInjection\RegisterMessageHandlersPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $projectDir = $this->getProjectDir();
        $confDir = $projectDir . '/config';

        $container->parameters()
            ->set('container.dumper.inline_class_loader', \PHP_VERSION_ID < 70400 || $this->debug)
            ->set('container.dumper.inline_factories', true)
        ;

        $container->import($confDir . '/{packages}/*' . self::CONFIG_EXTS);
        $container->import($confDir . '/{packages}/' . $this->environment . '/*' . self::CONFIG_EXTS);
        $container->import($confDir . '/{services}' . self::CONFIG_EXTS);
        $container->import($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS);

        // TODO: refactor services registering
        $services = $container->services();

        $servicePatterns = [
            'Ui/**/*Command',
            'Ui/**/*Guard',
            'Infrastructure/**/GuardAdapter/*GuardAdapter',
            'Infrastructure/**/Query/*Projector',
            'Infrastructure/**/Query/*Provider',
            'Infrastructure/**/Query/**/*Repository',
            'Infrastructure/**/Specification/*Specification',
            'Infrastructure/**/Repository/*Store',
            'Ui/**/*Transformer',
            'Infrastructure/**/Request/*RequestTransformerAdapter',
            'Infrastructure/**/View/*TransformerAdapter',
        ];

        foreach ([
            'IdentityAccess\\',
        ] as $namespacePrefix) {
            $services->load(
                $namespacePrefix,
                $projectDir . '/src/' . strtr($namespacePrefix, '\\', '/') . '{' . implode(',', $servicePatterns) . '}.php'
            )
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
    }
}
