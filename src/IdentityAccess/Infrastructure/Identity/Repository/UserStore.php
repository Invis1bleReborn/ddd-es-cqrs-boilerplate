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

namespace IdentityAccess\Infrastructure\Identity\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\ReflectionAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use IdentityAccess\Domain\Identity\Repository\UserRepositoryInterface;
use IdentityAccess\Domain\Identity\User;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserStore.
 */
final class UserStore extends EventSourcingRepository implements UserRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    )
    {
        parent::__construct(
            $eventStore,
            $eventBus,
            User::class,
            new ReflectionAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function get(UserId $id): User
    {
        $user = $this->load((string)$id);
        /* @var $user User */

        return $user;
    }

    public function store(User $user): void
    {
        $this->save($user);
    }
}
