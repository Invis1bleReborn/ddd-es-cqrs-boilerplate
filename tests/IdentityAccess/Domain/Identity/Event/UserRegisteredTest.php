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
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;

/**
 * Class UserRegisteredTest.
 */
class UserRegisteredTest extends UserIdGeneratorAwareSerializableEventTestCase
{
    protected function createEvent(): UserRegistered
    {
        return new UserRegistered(
            $this->generateUserId(),
            Email::fromString('alice@acme.com'),
            HashedPassword::fromString('some hash'),
            Roles::fromArray(['ROLE_USER']),
            true,
            $this->generateUserId(),
            DateTime::now()
        );
    }
}
