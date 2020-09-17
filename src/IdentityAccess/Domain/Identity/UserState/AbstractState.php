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

namespace IdentityAccess\Domain\Identity\UserState;

use Broadway\EventSourcing\SimpleEventSourcedEntity;
use IdentityAccess\Domain\Identity\Exception\UserStateTransitionException;
use IdentityAccess\Domain\Identity\User;

/**
 * Class AbstractState
 *
 * @package IdentityAccess\Domain\Identity\UserState
 */
abstract class AbstractState extends SimpleEventSourcedEntity implements UserStateInterface
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function changeUserState(UserStateInterface $state): void
    {
        $this->user->changeState($state);
    }

    protected function throwTransitionException(string $transition, string $state = null): void
    {
        throw new UserStateTransitionException(
            $this->user->id(),
            $state ?? get_class($this),
            $transition
        );
    }
}
