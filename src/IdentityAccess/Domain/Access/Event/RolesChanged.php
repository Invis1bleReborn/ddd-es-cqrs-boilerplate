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

use Assert\Assertion;
use Assert\AssertionFailedException;
use Common\Shared\Domain\Exception\DateTimeException;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\Event\UserIdAwareEvent;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class RolesChanged
 */
final class RolesChanged extends UserIdAwareEvent
{
    private Roles $roles;

    private Roles $previousRoles;

    private ?UserId $changedBy;

    private DateTime $dateChanged;

    public function __construct(
        UserId $id,
        Roles $roles,
        Roles $previousRoles,
        ?UserId $changedBy,
        DateTime $dateChanged
    )
    {
        parent::__construct($id);
        
        $this->roles = $roles;
        $this->previousRoles = $previousRoles;
        $this->changedBy = $changedBy;
        $this->dateChanged = $dateChanged;
    }

    public function roles(): Roles
    {
        return $this->roles;
    }

    public function previousRoles(): Roles
    {
        return $this->previousRoles;
    }

    public function changedBy(): ?UserId
    {
        return $this->changedBy;
    }

    public function dateChanged(): DateTime
    {
        return $this->dateChanged;
    }

    /**
     * @param array $data
     *
     * @return self
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'roles');
        Assertion::keyExists($data, 'previousRoles');
        Assertion::nullOrKeyExists($data, 'changedBy');
        Assertion::keyExists($data, 'dateChanged');

        return new self(
            UserId::fromString($data['id']),
            Roles::fromArray($data['roles']),
            Roles::fromArray($data['previousRoles']),
            isset($data['changedBy']) ? UserId::fromString($data['changedBy']) : null,
            DateTime::fromString($data['dateChanged']),
        );
    }

    public function serialize(): array
    {
        return parent::serialize() + [
            'roles' => $this->roles->toArray(),
            'previousRoles' => $this->previousRoles->toArray(),
            'dateChanged' => $this->dateChanged->toString(),
            'changedBy' => null === $this->changedBy ? null : $this->changedBy->toString(),
        ];
    }
}
