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

namespace IdentityAccess\Infrastructure\Identity\Query;

use Broadway\ReadModel\Testing\SerializableReadModelTestCase;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Common\Shared\Domain\ValueObject\DateTime;
use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;
use Common\Shared\Infrastructure\Uuid\UuidGenerator;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserTest.
 */
class UserTest extends SerializableReadModelTestCase
{
    private UuidGeneratorInterface $uuidGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uuidGenerator = new UuidGenerator(new Version4Generator());
    }

    protected function createSerializableReadModel(): User
    {
        return new User(
            UserId::fromString(($this->uuidGenerator)()),
            Email::fromString('alice@acme.com'),
            HashedPassword::fromString('some hash'),
            Roles::fromArray(['ROLE_SUPER_ADMIN']),
            true,
            UserId::fromString(($this->uuidGenerator)()),
            DateTime::now()
        );
    }
}
