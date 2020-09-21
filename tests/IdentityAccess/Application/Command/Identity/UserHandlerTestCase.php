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

namespace IdentityAccess\Application\Command\Identity;

use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventStore\InMemoryEventStore;
use Broadway\EventStore\TraceableEventStore;
use Common\Shared\Application\Command\UuidGeneratorAwareCommandHandlerScenarioTestCase;

/**
 * Class UserHandlerTestCase.
 *
 * @property UserAwareScenario $scenario
 */
abstract class UserHandlerTestCase extends UuidGeneratorAwareCommandHandlerScenarioTestCase
{
    protected function createScenario(): UserAwareScenario
    {
        $eventStore = new TraceableEventStore(new InMemoryEventStore());
        $eventBus = new SimpleEventBus();
        $commandHandler = $this->createCommandHandler($eventStore, $eventBus);

        return new UserAwareScenario($this, $eventStore, $commandHandler);
    }

    protected function generateUserId(): string
    {
        return $this->generateUuid();
    }

}
