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

use Broadway\EventHandling\EventListener;
use Common\Shared\Application\Bus\Command\CommandHandlerInterface;
use Common\Shared\Application\Bus\Event\EventHandlerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RegisterMessageHandlersPass
 *
 * @package Common\Shared\Infrastructure\DependencyInjection
 */
class RegisterMessageHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $this->registerMessageHandler($container, CommandHandlerInterface::class, 'messenger.bus.command');
//        $this->registerMessageHandler($container, QueryHandlerInterface::class, 'messenger.bus.query');
        $this->registerMessageHandler($container, EventHandlerInterface::class, 'messenger.bus.event.async');
        $this->registerMessageHandler($container, EventListener::class, null, 'broadway.domain.event_listener');
    }

    protected function registerMessageHandler(
        ContainerBuilder $container,
        string $fqin,
        string $bus = null,
        string $tag = 'messenger.message_handler'
    ): void
    {
        $tagAttributes = [];

        if (null !== $bus) {
            $tagAttributes['bus'] = $bus;
        }

        $container->registerForAutoconfiguration($fqin)
            ->setPublic(true)
            ->addTag($tag, $tagAttributes)
        ;
    }

}
