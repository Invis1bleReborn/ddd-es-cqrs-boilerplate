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

namespace IdentityAccess\Application\Command\Identity\DisableUser;

use Broadway\CommandHandling\CommandHandler;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Command\Identity\UserHandlerTestCase;
use IdentityAccess\Domain\Identity\Event\UserDisabled;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Identity\Command\DisableUser\DisableUserHandlerAdapter;
use IdentityAccess\Infrastructure\Identity\Repository\UserStore;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class DisableUserHandlerTest.
 */
class DisableUserHandlerTest extends UserHandlerTestCase
{
    /**
     * @test
     */
    public function itCanDisableUser(): void
    {
        $id = $this->generateUserId();
        $disabledById = $this->generateUserId();
        $dateDisabled = DateTime::now();

        $disableUser = new DisableUserCommand(
            $id,
            $disabledById
        );

        ClockMock::withClockMock($dateDisabled->toSeconds());

        $this->scenario
            ->givenUserRegistered($id)
            ->when($disableUser)
            ->then([
                new UserDisabled(
                    UserId::fromString($id),
                    UserId::fromString($disabledById),
                    $dateDisabled
                ),
            ])
            ->when($disableUser)
            ->then([]);
    }

    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        return new DisableUserHandlerAdapter(
            new DisableUserHandler(
                new UserStore($eventStore, $eventBus)
            )
        );
    }
}
