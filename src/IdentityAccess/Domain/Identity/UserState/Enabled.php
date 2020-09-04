<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\UserState;

use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Identity\Event\UserDisabled;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class Enabled
 *
 * @package IdentityAccess\Domain\Identity\UserState
 */
class Enabled extends AbstractState
{
    public function setDisabled(?UserId $disabledBy, DateTime $dateDisabled): void
    {
        $this->user->apply(new UserDisabled(
            $this->user->id(),
            $disabledBy,
            $dateDisabled
        ));
    }

    public function setEnabled(?UserId $enabledBy, DateTime $dateEnabled): void
    {
        // no op
    }

    public function assertEnabled(): void
    {
        // no op
    }

    protected function applyUserDisabled(): void
    {
        $this->changeUserState(new Disabled($this->user));
    }

}
