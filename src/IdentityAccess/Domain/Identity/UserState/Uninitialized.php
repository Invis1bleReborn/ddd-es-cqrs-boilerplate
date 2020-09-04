<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\UserState;

use Assert\Assertion;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Identity\Event\UserRegistered;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class Uninitialized
 *
 * @package IdentityAccess\Domain\Identity\UserState
 */
class Uninitialized extends AbstractState
{
    public function setDisabled(?UserId $disabledBy, DateTime $dateDisabled): void
    {
        $this->throwTransitionException(__FUNCTION__);
    }

    public function setEnabled(?UserId $enabledBy, DateTime $dateEnabled): void
    {
        $this->throwTransitionException(__FUNCTION__);
    }

    public function assertEnabled(): void
    {
        Assertion::true(false, sprintf(
            'User %s "%s" state is not initialized.',
            $this->user->id()->toString(),
            $this->user->email()->toString()
        ));
    }

    protected function applyUserRegistered(UserRegistered $event): void
    {
        if ($event->enabled()) {
            $state = new Enabled($this->user);
        } else {
            $state = new Disabled($this->user);
        }

        $this->changeUserState($state);
    }

}
