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

namespace IdentityAccess\Domain\Identity;

use Common\Shared\Domain\UuidGeneratorAwareAggregateRootScenarioTestCase;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\Event\UserDisabled;
use IdentityAccess\Domain\Identity\Event\UserEnabled;
use IdentityAccess\Domain\Identity\Event\UserRegistered;
use IdentityAccess\Domain\Identity\Exception\EmailAlreadyExistsException;
use IdentityAccess\Domain\Identity\Specification\UniqueEmailSpecificationInterface;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use PHPUnit\Framework\MockObject\Stub;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class UserTest.
 */
class UserTest extends UuidGeneratorAwareAggregateRootScenarioTestCase
{
    /**
     * @test
     */
    public function itCanBeRegistered(): UserRegistered
    {
        $id = $this->generateUserId();
        $email = Email::fromString('alice@acme.com');
        $hashedPassword = HashedPassword::fromString('some hash');
        $roles = Roles::fromArray([]);
        $enabled = true;
        $registeredById = $this->generateUserId();
        $dateRegistered = DateTime::now();

        $userRegistered = new UserRegistered(
            $id,
            $email,
            $hashedPassword,
            $roles,
            $enabled,
            $registeredById,
            $dateRegistered
        );

        ClockMock::withClockMock($dateRegistered->toSeconds());

        $this->scenario
            ->when(fn (): User => User::register(
                $id,
                $email,
                $hashedPassword,
                $roles,
                $enabled,
                $registeredById,
                $this->createUniqueEmailSpecificationStub(true)
            ))
            ->then([$userRegistered]);

        return $userRegistered;
    }

    /**
     * @test
     */
    public function itCantBeRegisteredWithNonUniqueEmail(): void
    {
        $this->expectException(EmailAlreadyExistsException::class);

        $this->scenario
            ->when(fn (): User => User::register(
                $this->generateUserId(),
                Email::fromString('alice@acme.com'),
                HashedPassword::fromString('some hash'),
                Roles::fromArray([]),
                true,
                $this->generateUserId(),
                $this->createUniqueEmailSpecificationStub(false)
            ))
        ;
    }

    /**
     * @test
     * @depends itCanBeRegistered
     */
    public function enabledUserCanBeDisabled(UserRegistered $userRegistered): UserDisabled
    {
        $id = $userRegistered->id();
        $disabledById = $this->generateUserId();
        $dateDisabled = DateTime::now();

        $disableUser = fn (User $user) => $user->disable($disabledById);

        $userDisabled = new UserDisabled(
            $id,
            $disabledById,
            $dateDisabled
        );

        ClockMock::withClockMock($dateDisabled->toSeconds());

        $this->scenario
            ->withAggregateId($id->toString())
            ->given([
                $userRegistered,
            ])
            ->when($disableUser)
            ->then([$userDisabled])
            ->when($disableUser)
            ->then([]);

        return $userDisabled;
    }

    /**
     * @test
     * @depends itCanBeRegistered
     * @depends enabledUserCanBeDisabled
     */
    public function disabledUserCanBeEnabled(
        UserRegistered $userRegistered,
        UserDisabled $userDisabled
    ): UserEnabled {
        $id = $userRegistered->id();
        $enabledById = $this->generateUserId();
        $dateEnabled = DateTime::now();

        $enableUser = fn (User $user) => $user->enable($enabledById);

        $userEnabled = new UserEnabled(
            $id,
            $enabledById,
            $dateEnabled
        );

        ClockMock::withClockMock($dateEnabled->toSeconds());

        $this->scenario
            ->withAggregateId($id->toString())
            ->given([
                $userRegistered,
                $userDisabled,
            ])
            ->when($enableUser)
            ->then([$userEnabled])
            ->when($enableUser)
            ->then([]);

        return $userEnabled;
    }

    /**
     * @return UniqueEmailSpecificationInterface|Stub
     */
    protected function createUniqueEmailSpecificationStub(bool $isUnique): UniqueEmailSpecificationInterface
    {
        $specificationStub = $this->createStub(UniqueEmailSpecificationInterface::class);

        $specificationStub->method('isUnique')
            ->willReturn($isUnique);

        return $specificationStub;
    }

    protected function generateUserId(): UserId
    {
        return UserId::fromString($this->generateUuid());
    }

    protected function getAggregateRootClass(): string
    {
        return User::class;
    }
}
