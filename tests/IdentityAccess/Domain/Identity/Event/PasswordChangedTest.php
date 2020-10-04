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

namespace IdentityAccess\Domain\Identity\Event;

use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;

/**
 * Class PasswordChangedTest.
 */
class PasswordChangedTest extends UserIdGeneratorAwareSerializableEventTestCase
{
    protected function createEvent(): PasswordChanged
    {
        return new PasswordChanged(
            $this->generateUserId(),
            HashedPassword::fromString('new hash'),
            HashedPassword::fromString('some hash'),
            $this->generateUserId(),
            DateTime::now()
        );
    }
}
