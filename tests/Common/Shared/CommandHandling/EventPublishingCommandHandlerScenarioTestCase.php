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
use Common\Shared\Application\Bus\Event\EventBusInterface;
use Common\Shared\Domain\ValueObject\DateTime;
use Common\Shared\Infrastructure\Bus\EventBus;
use Common\Shared\Infrastructure\Bus\TraceableEventBus;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;
use Symfony\Component\Messenger\MessageBus;

/**
 * Class EventPublishingCommandHandlerScenarioTestCase.
 */
abstract class EventPublishingCommandHandlerScenarioTestCase extends TestCase
{
    protected Scenario $scenario;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        ClockMock::register(DateTime::class);
    }

    protected function setUp(): void
    {
        $this->scenario = $this->createScenario();
    }

    protected function createScenario(): Scenario
    {
        $eventBus = new TraceableEventBus(new EventBus(new MessageBus()));
        $commandHandler = $this->createCommandHandler($eventBus);

        return new Scenario($this, $eventBus, $commandHandler);
    }

    /**
     * Create a command handler for the given scenario test case.
     */
    abstract protected function createCommandHandler(EventBusInterface $eventBus): CommandHandlerInterface;
}
