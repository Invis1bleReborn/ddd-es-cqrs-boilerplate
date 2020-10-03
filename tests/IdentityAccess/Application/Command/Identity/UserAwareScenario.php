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

namespace IdentityAccess\Application\Command\Identity;

use Broadway\CommandHandling\Testing\Scenario;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\Event\UserRegistered;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserAwareScenario.
 */
class UserAwareScenario extends Scenario
{
    public function givenUserRegistered(
        string $userId,
        string $hashedPassword = null,
        array $roles = [],
        bool $enabled = true,
        string $registeredById = null,
        DateTime $dateRegistered = null
    ) {
        return $this
            ->withAggregateId($userId)
            ->given([
                new UserRegistered(
                    UserId::fromString($userId),
                    Email::fromString('alice@acme.com'),
                    HashedPassword::fromString($hashedPassword ?? 'some hash'),
                    Roles::fromArray($roles),
                    $enabled,
                    null === $registeredById ? null : UserId::fromString($registeredById),
                    null === $dateRegistered ? DateTime::now() : $dateRegistered
                ),
            ]);
    }
}
