<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IdentityAccess\Domain\Identity\UserState;

use Assert\Assertion;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Identity\Event\UserEnabled;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class Disabled
 *
 * @package IdentityAccess\Domain\Identity\UserState
 */
class Disabled extends AbstractState
{
    public function setDisabled(?UserId $disabledBy, DateTime $dateDisabled): void
    {
        // no op
    }

    public function setEnabled(?UserId $enabledBy, DateTime $dateEnabled): void
    {
        $this->user->apply(new UserEnabled(
            $this->user->id(),
            $enabledBy,
            $dateEnabled
        ));
    }

    public function assertEnabled(): void
    {
        Assertion::true(false, sprintf(
            'User %s "%s" is disabled.',
            $this->user->id()->toString(),
            $this->user->email()->toString()
        ));
    }

    protected function applyUserEnabled(): void
    {
        $this->changeUserState(new Enabled($this->user));
    }

}
