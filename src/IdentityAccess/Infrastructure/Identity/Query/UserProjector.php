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
use IdentityAccess\Domain\Identity\Event\UserDisabled;
use IdentityAccess\Domain\Identity\Event\UserEnabled;
use IdentityAccess\Domain\Identity\Event\UserRegistered;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Identity\Query\Orm\OrmUserReadModelRepository;

/**
 * Class UserProjector.
 */
class UserProjector extends Projector
{
    private OrmUserReadModelRepository $repository;

    public function __construct(OrmUserReadModelRepository $repository)
    {
        $this->repository = $repository;
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

    protected function getUser(UserId $userId): User
    {
        return $this->repository->oneById($userId);
    }

    protected function saveUser(User $user): void
    {
        $this->repository->add($user);
    }
}
