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

use Assert\Assertion;
use Assert\AssertionFailedException;
use Common\Shared\Domain\Exception\DateTimeException;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserRegistered
 */
final class UserRegistered extends UserIdAwareEvent
{
    private Email $email;

    private HashedPassword $hashedPassword;

    private Roles $roles;

    private bool $enabled;

    private ?UserId $registeredBy;

    private DateTime $dateRegistered;

    public function __construct(
        UserId $id,
        Email $email,
        HashedPassword $hashedPassword,
        Roles $roles,
        bool $enabled,
        ?UserId $registeredBy,
        DateTime $dateRegistered
    )
    {
        parent::__construct($id);

        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
        $this->roles = $roles;
        $this->enabled = $enabled;
        $this->registeredBy = $registeredBy;
        $this->dateRegistered = $dateRegistered;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function hashedPassword(): HashedPassword
    {
        return $this->hashedPassword;
    }

    public function roles(): Roles
    {
        return $this->roles;
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function registeredBy(): ?UserId
    {
        return $this->registeredBy;
    }

    public function dateRegistered(): DateTime
    {
        return $this->dateRegistered;
    }

    /**
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'email');
        Assertion::keyExists($data, 'hashedPassword');
        Assertion::keyExists($data, 'roles');
        Assertion::keyExists($data, 'enabled');
        Assertion::nullOrKeyExists($data, 'registeredBy');
        Assertion::keyExists($data, 'dateRegistered');

        return new self(
            UserId::fromString($data['id']),
            Email::fromString($data['email']),
            HashedPassword::fromString($data['hashedPassword']),
            Roles::fromArray($data['roles']),
            $data['enabled'],
            isset($data['registeredBy']) ? UserId::fromString($data['registeredBy']) : null,
            DateTime::fromString($data['dateRegistered']),
        );
    }

    public function serialize(): array
    {
        return parent::serialize() + [
            'email' => $this->email->toString(),
            'hashedPassword' => $this->hashedPassword->toString(),
            'roles' => $this->roles->toArray(),
            'enabled' => $this->enabled,
            'dateRegistered' => $this->dateRegistered->toString(),
            'registeredBy' => null === $this->registeredBy ? null : $this->registeredBy->toString(),
        ];
    }
}
