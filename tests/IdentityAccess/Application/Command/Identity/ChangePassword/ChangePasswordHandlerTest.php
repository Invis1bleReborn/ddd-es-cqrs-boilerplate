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

namespace IdentityAccess\Application\Command\Identity\ChangePassword;

use Broadway\CommandHandling\CommandHandler;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Command\Identity\UserHandlerTestCase;
use IdentityAccess\Domain\Identity\Event\PasswordChanged;
use IdentityAccess\Domain\Identity\PasswordEncoderInterface;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Identity\Command\ChangePassword\ChangePasswordHandlerAdapter;
use IdentityAccess\Infrastructure\Identity\Password\NoopPasswordEncoder;
use IdentityAccess\Infrastructure\Identity\Repository\UserStore;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class ChangePasswordHandlerTest.
 */
class ChangePasswordHandlerTest extends UserHandlerTestCase
{
    private ?PasswordEncoderInterface $passwordEncoder;

    /**
     * @test
     */
    public function itCanChangePassword(): void
    {
        $id = $this->generateUserId();
        $plainPassword = 'some password';
        $previousHashedPassword = 'some hash';
        $changedById = $this->generateUserId();
        $dateChanged = DateTime::now();

        $changePassword = new ChangePasswordCommand(
            $id,
            $plainPassword,
            $changedById
        );

        ClockMock::withClockMock($dateChanged->toSeconds());

        $this->scenario
            ->givenUserRegistered($id, $previousHashedPassword)
            ->when($changePassword)
            ->then([
                new PasswordChanged(
                    UserId::fromString($id),
                    $this->passwordEncoder->encode(PlainPassword::fromString($plainPassword)),
                    HashedPassword::fromString($previousHashedPassword),
                    UserId::fromString($changedById),
                    $dateChanged
                ),
            ])
            ->when($changePassword)
            ->then([]);
    }

    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        $this->passwordEncoder = new NoopPasswordEncoder();

        return new ChangePasswordHandlerAdapter(
            new ChangePasswordHandler(
                new UserStore($eventStore, $eventBus),
                $this->passwordEncoder
            )
        );
    }
}
