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

namespace IdentityAccess\Application\Event\Access;

use Broadway\Serializer\Testing\SerializableEventTestCase;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\ValueObject\RefreshToken;
use IdentityAccess\Domain\Identity\ValueObject\Username;

/**
 * Class TokenCreatedTest.
 */
class TokenCreatedTest extends SerializableEventTestCase
{
    protected function createEvent(): TokenCreated
    {
        return new TokenCreated(
            RefreshToken::fromString('refresh token'),
            Username::fromString('alice@acme.com'),
            DateTime::now()
        );
    }
}
