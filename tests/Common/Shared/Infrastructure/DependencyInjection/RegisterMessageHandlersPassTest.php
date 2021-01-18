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
use Common\Shared\Application\Command\CommandHandlerInterface;
use Common\Shared\Application\Event\EventHandlerInterface;
use Common\Shared\Application\Query\QueryHandlerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RegisterMessageHandlersPassTest.
 */
class RegisterMessageHandlersPassTest extends TestCase
{
    /**
     * @dataProvider processDataProvider
     */
    public function testProcess(
        string $fqin,
        string $bus = null,
        string $tag = 'messenger.message_handler'
    ): void {
        $container = new ContainerBuilder();

        $this->process($container);

        $definitions = $container->getAutoconfiguredInstanceof();

        $this->assertArrayHasKey($fqin, $definitions, sprintf('Interface "%s" is not autoconfigured.', $fqin));
        $this->assertTrue(
            $definitions[$fqin]->hasTag($tag),
            sprintf('Interface "%s" must be tagged as "%s".', $fqin, $tag)
        );

        if (null !== $bus) {
            $tag = $definitions[$fqin]->getTag($tag)[0];

            $this->assertArrayHasKey('bus', $tag, sprintf('Interface "%s" tag must have attribute "bus".', $fqin));
            $this->assertSame(
                $bus,
                $tag['bus'],
                sprintf('Command handler definition tag attribute "bus" must be "%s".', $bus)
            );
        }
    }

    public function processDataProvider(): array
    {
        return [
            [CommandHandlerInterface::class, 'messenger.bus.command'],
            [QueryHandlerInterface::class, 'messenger.bus.query'],
            [EventHandlerInterface::class, 'messenger.bus.event.async'],
            [EventListener::class, null, 'broadway.domain.event_listener'],
        ];
    }

    protected function process(ContainerBuilder $container): void
    {
        (new RegisterMessageHandlersPass())
            ->process($container);
    }
}
