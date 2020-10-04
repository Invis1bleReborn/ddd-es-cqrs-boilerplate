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

namespace IdentityAccess\Domain\Access\Event;

use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\Event\UserIdGeneratorAwareSerializableEventTestCase;

/**
 * Class RolesChangedTest.
 */
class RolesChangedTest extends UserIdGeneratorAwareSerializableEventTestCase
{
    protected function createEvent(): RolesChanged
    {
        return new RolesChanged(
            $this->generateUserId(),
            Roles::fromArray(['ROLE_SUPER_ADMIN']),
            Roles::fromArray(['ROLE_USER']),
            $this->generateUserId(),
            DateTime::now()
        );
    }
}
