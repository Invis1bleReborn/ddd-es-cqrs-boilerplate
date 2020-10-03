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

use Broadway\ReadModel\Projector;
use Broadway\ReadModel\Repository;
use IdentityAccess\Domain\Access\Event\RolesChanged;
use IdentityAccess\Domain\Identity\Event\EmailChanged;
use IdentityAccess\Domain\Identity\Event\PasswordChanged;
use IdentityAccess\Domain\Identity\Event\UserDisabled;
use IdentityAccess\Domain\Identity\Event\UserEnabled;
use IdentityAccess\Domain\Identity\Event\UserRegistered;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserProjector.
 */
class UserProjector extends Projector
{
    private Repository $userRepository;

    public function __construct(Repository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function applyUserRegistered(UserRegistered $event): void
    {
        $user = new User(
            $event->id(),
            $event->email(),
            $event->hashedPassword(),
            $event->roles(),
            $event->enabled(),
            $event->registeredBy(),
            $event->dateRegistered()
        );

        $this->saveUser($user);
    }

    protected function applyUserEnabled(UserEnabled $event): void
    {
        $user = $this->getUser($event->id());

        $user->setEnabled(true);

        $this->saveUser($user);
    }

    protected function applyUserDisabled(UserDisabled $event): void
    {
        $user = $this->getUser($event->id());

        $user->setEnabled(false);

        $this->saveUser($user);
    }

    protected function applyEmailChanged(EmailChanged $event): void
    {
        $user = $this->getUser($event->id());

        $user->setEmail($event->email());

        $this->saveUser($user);
    }

    protected function applyPasswordChanged(PasswordChanged $event): void
    {
        $user = $this->getUser($event->id());

        $user->setHashedPassword($event->hashedPassword());

        $this->saveUser($user);
    }

    protected function applyRolesChanged(RolesChanged $event): void
    {
        $user = $this->getUser($event->id());

        $user->setRoles($event->roles());

        $this->saveUser($user);
    }

    protected function getUser(UserId $userId): ?User
    {
        $user = $this->userRepository->find($userId->toString());
        /* @var $user User|null */

        return $user;
    }

    protected function saveUser(User $user): void
    {
        $this->userRepository->save($user);
    }
}
