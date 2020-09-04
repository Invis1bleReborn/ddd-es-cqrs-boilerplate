<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\UserState;

use Assert\AssertionFailedException;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Interface UserStateInterface
 *
 * @package IdentityAccess\Domain\Identity\UserState
 */
interface UserStateInterface
{
    public function changeUserState(UserStateInterface $state): void;

    public function setDisabled(?UserId $disabledBy, DateTime $dateDisabled): void;

    public function setEnabled(?UserId $enabledBy, DateTime $dateEnabled): void;

    /**
     * @throws AssertionFailedException
     */
    public function assertEnabled(): void;

}
