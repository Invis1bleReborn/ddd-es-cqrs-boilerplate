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

use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Identity\Event\UserDisabled;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class Enabled
 *
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
