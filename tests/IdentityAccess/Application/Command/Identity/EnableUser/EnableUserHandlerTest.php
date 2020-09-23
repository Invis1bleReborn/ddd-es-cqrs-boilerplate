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

namespace IdentityAccess\Application\Command\Identity\EnableUser;

use Broadway\CommandHandling\CommandHandler;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Command\Identity\UserHandlerTestCase;
use IdentityAccess\Domain\Identity\Event\UserEnabled;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Identity\Command\EnableUser\EnableUserHandlerAdapter;
use IdentityAccess\Infrastructure\Identity\Repository\UserStore;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class EnableUserHandlerTest.
 */
class EnableUserHandlerTest extends UserHandlerTestCase
{
    /**
     * @test
     */
    public function itCanEnableUser(): void
    {
        $id = $this->generateUserId();
        $enabledById = $this->generateUserId();
        $dateEnabled = DateTime::now();

        $enableUser = new EnableUserCommand(
            $id,
            $enabledById
        );

        ClockMock::withClockMock($dateEnabled->toSeconds());

        $this->scenario
            ->givenUserRegistered($id, false)
            ->when($enableUser)
            ->then([
                new UserEnabled(
                    UserId::fromString($id),
                    UserId::fromString($enabledById),
                    $dateEnabled
                ),
            ])
            ->when($enableUser)
            ->then([]);
    }

    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        return new EnableUserHandlerAdapter(
            new EnableUserHandler(
                new UserStore($eventStore, $eventBus)
            )
        );
    }
}
