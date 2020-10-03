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

namespace IdentityAccess\Infrastructure\Identity\Query;

use Broadway\ReadModel\InMemory\InMemoryRepository;
use Broadway\ReadModel\Projector;
use Common\Shared\Application\Query\UuidGeneratorAwareProjectorScenarioTestCase;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\Event\RolesChanged;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\Event\PasswordChanged;
use IdentityAccess\Domain\Identity\Event\UserDisabled;
use IdentityAccess\Domain\Identity\Event\UserEnabled;
use IdentityAccess\Domain\Identity\Event\UserRegistered;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserProjectorTest.
 */
class UserProjectorTest extends UuidGeneratorAwareProjectorScenarioTestCase
{
    /**
     * @test
     */
    public function itCreatesReadModelWhenUserRegistered(): array
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

        $user = new User(
            $id,
            $email,
            $hashedPassword,
            $roles,
            $enabled,
            $registeredById,
            $dateRegistered
        );

        $this->scenario
            ->when($userRegistered)
            ->then([
                $user,
            ])
        ;

        return [
            $userRegistered,
            $user,
        ];
    }

    /**
     * @test
     * @depends itCreatesReadModelWhenUserRegistered
     */
    public function itUpdatesReadModelWhenUserDisabled($deps)
    {
        [$userRegistered, $user] = $deps;
        /* @var $userRegistered UserRegistered */
        /* @var $user User */

        $userId = $userRegistered->id();

        $user->setEnabled(false);

        $this->scenario
            ->withAggregateId($userId->toString())
            ->given([
                $userRegistered,
            ])
            ->when(new UserDisabled(
                $userId,
                $this->generateUserId(),
                DateTime::now()
            ))
            ->then([
                $user,
            ])
        ;
    }

    /**
     * @test
     */
    public function itUpdatesReadModelWhenUserEnabled()
    {
        $id = $this->generateUserId();
        $email = Email::fromString('alice@acme.com');
        $hashedPassword = HashedPassword::fromString('some hash');
        $roles = Roles::fromArray([]);
        $enabled = false;
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

        $this->scenario
            ->withAggregateId($id->toString())
            ->given([
                $userRegistered,
            ])
            ->when(new UserEnabled(
                $id,
                $this->generateUserId(),
                DateTime::now()
            ))
            ->then([
                new User(
                    $id,
                    $email,
                    $hashedPassword,
                    $roles,
                    true,
                    $registeredById,
                    $dateRegistered
                ),
            ])
        ;
    }

    /**
     * @test
     * @depends itCreatesReadModelWhenUserRegistered
     */
    public function itUpdatesReadModelWhenPasswordChanged($deps)
    {
        [$userRegistered, $user] = $deps;
        /* @var $userRegistered UserRegistered */
        /* @var $user User */

        $userId = $userRegistered->id();
        $hashedPassword = HashedPassword::fromString('new hash');

        $user->setHashedPassword($hashedPassword);

        $this->scenario
            ->withAggregateId($userId->toString())
            ->given([
                $userRegistered,
            ])
            ->when(new PasswordChanged(
                $userId,
                $hashedPassword,
                $userRegistered->hashedPassword(),
                $this->generateUserId(),
                DateTime::now()
            ))
            ->then([
                $user,
            ])
        ;
    }

    /**
     * @test
     * @depends itCreatesReadModelWhenUserRegistered
     */
    public function itUpdatesReadModelWhenRolesChanged($deps)
    {
        [$userRegistered, $user] = $deps;
        /* @var $userRegistered UserRegistered */
        /* @var $user User */

        $userId = $userRegistered->id();
        $roles = Roles::fromArray(['ROLE_SUPER_ADMIN']);

        $user->setRoles($roles);

        $this->scenario
            ->withAggregateId($userId->toString())
            ->given([
                $userRegistered,
            ])
            ->when(new RolesChanged(
                $userId,
                $roles,
                $userRegistered->roles(),
                $this->generateUserId(),
                DateTime::now()
            ))
            ->then([
                $user,
            ])
        ;
    }

    protected function generateUserId(): UserId
    {
        return UserId::fromString($this->generateUuid());
    }

    protected function createProjector(InMemoryRepository $repository): Projector
    {
        return new UserProjector($repository);
    }
}
