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

namespace IdentityAccess\Application\Command\Identity\ChangeEmail;

use Broadway\CommandHandling\CommandHandler;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Command\Identity\UserHandlerTestCase;
use IdentityAccess\Domain\Identity\Event\EmailChanged;
use IdentityAccess\Domain\Identity\Exception\EmailAlreadyExistsException;
use IdentityAccess\Domain\Identity\Specification\UniqueEmailSpecificationInterface;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Identity\Command\ChangeEmail\ChangeEmailHandlerAdapter;
use IdentityAccess\Infrastructure\Identity\Repository\UserStore;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class ChangeEmailHandlerTest.
 */
class ChangeEmailHandlerTest extends UserHandlerTestCase
{
    /**
     * @var UniqueEmailSpecificationInterface|InvocationMocker|null
     */
    private ?UniqueEmailSpecificationInterface $uniqueEmailSpecificationStub;

    /**
     * @test
     */
    public function itChangesUserEmail(): void
    {
        $id = $this->generateUserId();
        $email = 'newalice@acme.com';
        $previousEmail = 'alice@acme.com';
        $changedById = $this->generateUserId();
        $dateChanged = DateTime::now();

        $changeEmail = new ChangeEmailCommand(
            $id,
            $email,
            $changedById
        );

        $this->uniqueEmailSpecificationStub->method('isUnique')
            ->willReturn(true);

        ClockMock::withClockMock($dateChanged->toSeconds());

        $this->scenario
            ->givenUserRegistered($id, $previousEmail)
            ->when($changeEmail)
            ->then([
                new EmailChanged(
                    UserId::fromString($id),
                    Email::fromString($email),
                    Email::fromString($previousEmail),
                    UserId::fromString($changedById),
                    $dateChanged
                ),
            ])
            ->when($changeEmail)
            ->then([])
        ;
    }

    /**
     * @test
     */
    public function itDoesNotChangeUserEmailToNonUnique(): void
    {
        $this->expectException(EmailAlreadyExistsException::class);

        $id = $this->generateUserId();

        $this->uniqueEmailSpecificationStub->method('isUnique')
            ->willReturn(false);

        $this->scenario
            ->givenUserRegistered($id)
            ->when(new ChangeEmailCommand(
                $id,
                'newalice@acme.com',
                $this->generateUserId()
            ))
        ;
    }

    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        $this->uniqueEmailSpecificationStub = $this->createStub(UniqueEmailSpecificationInterface::class);

        return new ChangeEmailHandlerAdapter(
            new ChangeEmailHandler(
                new UserStore($eventStore, $eventBus),
                $this->uniqueEmailSpecificationStub
            )
        );
    }
}
