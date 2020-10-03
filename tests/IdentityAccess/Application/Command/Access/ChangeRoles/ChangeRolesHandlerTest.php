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

namespace IdentityAccess\Application\Command\Access\ChangeRoles;

use Broadway\CommandHandling\CommandHandler;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Command\Identity\UserHandlerTestCase;
use IdentityAccess\Domain\Access\Event\RolesChanged;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Access\Command\ChangeRoles\ChangeRolesHandlerAdapter;
use IdentityAccess\Infrastructure\Identity\Repository\UserStore;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class ChangeRolesHandlerTest.
 */
class ChangeRolesHandlerTest extends UserHandlerTestCase
{
    /**
     * @test
     */
    public function itCanChangeRoles(): void
    {
        $id = $this->generateUserId();
        $roles = ['ROLE_SUPER_ADMIN'];
        $previousRoles = ['ROLE_USER'];
        $changedById = $this->generateUserId();
        $dateChanged = DateTime::now();

        $changeRoles = new ChangeRolesCommand(
            $id,
            $roles,
            $changedById
        );

        ClockMock::withClockMock($dateChanged->toSeconds());

        $this->scenario
            ->givenUserRegistered($id, null, $previousRoles)
            ->when($changeRoles)
            ->then([
                new RolesChanged(
                    UserId::fromString($id),
                    Roles::fromArray($roles),
                    Roles::fromArray($previousRoles),
                    UserId::fromString($changedById),
                    $dateChanged
                ),
            ])
            ->when($changeRoles)
            ->then([]);
    }

    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        return new ChangeRolesHandlerAdapter(
            new ChangeRolesHandler(
                new UserStore($eventStore, $eventBus)
            )
        );
    }
}
