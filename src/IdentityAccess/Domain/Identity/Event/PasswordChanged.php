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
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class PasswordChanged.
 */
final class PasswordChanged extends UserIdAwareEvent
{
    private HashedPassword $hashedPassword;

    private HashedPassword $previousHashedPassword;

    private ?UserId $changedBy;

    private DateTime $dateChanged;

    public function __construct(
        UserId $id,
        HashedPassword $hashedPassword,
        HashedPassword $previousHashedPassword,
        ?UserId $changedBy,
        DateTime $dateChanged
    ) {
        parent::__construct($id);

        $this->hashedPassword = $hashedPassword;
        $this->previousHashedPassword = $previousHashedPassword;
        $this->changedBy = $changedBy;
        $this->dateChanged = $dateChanged;
    }

    public function hashedPassword(): HashedPassword
    {
        return $this->hashedPassword;
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
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'hashedPassword');
        Assertion::keyExists($data, 'previousHashedPassword');
        Assertion::nullOrKeyExists($data, 'changedBy');
        Assertion::keyExists($data, 'dateChanged');

        return new self(
            UserId::fromString($data['id']),
            HashedPassword::fromString($data['hashedPassword']),
            HashedPassword::fromString($data['previousHashedPassword']),
            isset($data['changedBy']) ? UserId::fromString($data['changedBy']) : null,
            DateTime::fromString($data['dateChanged']),
        );
    }

    public function serialize(): array
    {
        return parent::serialize() + [
            'hashedPassword' => $this->hashedPassword->toString(),
            'previousHashedPassword' => $this->previousHashedPassword->toString(),
            'dateChanged' => $this->dateChanged->toString(),
            'changedBy' => null === $this->changedBy ? null : $this->changedBy->toString(),
        ];
    }
}
