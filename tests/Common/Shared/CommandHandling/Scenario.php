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

namespace Common\Shared\CommandHandling;

use Common\Shared\Application\Bus\Command\CommandHandlerInterface;
use Common\Shared\Application\Bus\Command\CommandInterface;
use Common\Shared\Application\Bus\Event\EventInterface;
use Common\Shared\Infrastructure\Bus\TraceableEventBus;
use PHPUnit\Framework\TestCase;

/**
 * Class Scenario.
 */
class Scenario
{
    private TestCase $testCase;

    private TraceableEventBus $eventBus;

    private CommandHandlerInterface $commandHandler;

    public function __construct(
        TestCase $testCase,
        TraceableEventBus $eventBus,
        CommandHandlerInterface $commandHandler
    ) {
        $this->testCase = $testCase;
        $this->eventBus = $eventBus;
        $this->commandHandler = $commandHandler;
    }

    /**
     * @param EventInterface[] $events
     */
    public function given(?array $events)
    {
        if (null === $events) {
            return $this;
        }

        foreach ($events as $event) {
            $this->eventBus->handle($event);
        }

        return $this;
    }

    public function when(CommandInterface $command)
    {
        $this->eventBus->trace();

        ($this->commandHandler)($command);

        return $this;
    }

    /**
     * @param EventInterface[] $events
     */
    public function then(array $events)
    {
        $this->testCase->assertEquals($events, $this->eventBus->getRecordedEvents());

        return $this;
    }
}
