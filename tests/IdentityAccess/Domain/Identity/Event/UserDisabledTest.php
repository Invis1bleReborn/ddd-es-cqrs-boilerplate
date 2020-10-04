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

/**
 * Class UserDisabledTest.
 */
class UserDisabledTest extends UserIdGeneratorAwareSerializableEventTestCase
{
    protected function createEvent(): UserDisabled
    {
        return new UserDisabled(
            $this->generateUserId(),
            $this->generateUserId(),
            DateTime::now()
        );
    }
}
