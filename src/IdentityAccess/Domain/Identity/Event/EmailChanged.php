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
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class EmailChanged
 *
 * @package IdentityAccess\Domain\Identity\Event
 */
final class EmailChanged extends UserIdAwareEvent
{
    private Email $email;

    private Email $previousEmail;

    private ?UserId $changedBy;

    private DateTime $dateChanged;

    public function __construct(
        UserId $id,
        Email $email,
        Email $previousEmail,
        ?UserId $changedBy,
        DateTime $dateChanged
    )
    {
        parent::__construct($id);

        $this->email = $email;
        $this->previousEmail = $previousEmail;
        $this->changedBy = $changedBy;
        $this->dateChanged = $dateChanged;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function previousEmail(): Email
    {
        return $this->previousEmail;
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
        Assertion::keyExists($data, 'email');
        Assertion::keyExists($data, 'previousEmail');
        Assertion::nullOrKeyExists($data, 'changedBy');
        Assertion::keyExists($data, 'dateChanged');

        return new self(
            UserId::fromString($data['id']),
            Email::fromString($data['email']),
            Email::fromString($data['previousEmail']),
            isset($data['changedBy']) ? UserId::fromString($data['changedBy']) : null,
            DateTime::fromString($data['dateChanged']),
        );
    }

    public function serialize(): array
    {
        return parent::serialize() + [
            'email' => $this->email->toString(),
            'previousEmail' => $this->previousEmail->toString(),
            'dateChanged' => $this->dateChanged->toString(),
            'changedBy' => null === $this->changedBy ? null : $this->changedBy->toString(),
        ];
    }
}
