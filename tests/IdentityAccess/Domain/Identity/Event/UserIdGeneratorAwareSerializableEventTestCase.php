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

use Common\Shared\Domain\UuidGeneratorAwareSerializableEventTestCase;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserIdGeneratorAwareSerializableEventTestCase.
 */
abstract class UserIdGeneratorAwareSerializableEventTestCase extends UuidGeneratorAwareSerializableEventTestCase
{
    protected function generateUserId(): UserId
    {
        return UserId::fromString($this->generateUuid());
    }
}
