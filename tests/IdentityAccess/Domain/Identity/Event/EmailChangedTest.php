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
use IdentityAccess\Domain\Identity\ValueObject\Email;

/**
 * Class EmailChangedTest.
 */
class EmailChangedTest extends UserIdGeneratorAwareSerializableEventTestCase
{
    protected function createEvent(): EmailChanged
    {
        return new EmailChanged(
            $this->generateUserId(),
            Email::fromString('newalice@acme.com'),
            Email::fromString('alice@acme.com'),
            $this->generateUserId(),
            DateTime::now()
        );
    }
}
