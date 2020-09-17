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
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserEnabled.
 */
final class UserEnabled extends UserIdAwareEvent
{
    private ?UserId $enabledBy;

    private DateTime $dateEnabled;

    public function __construct(
        UserId $id,
        ?UserId $enabledBy,
        DateTime $dateEnabled
    ) {
        parent::__construct($id);

        $this->enabledBy = $enabledBy;
        $this->dateEnabled = $dateEnabled;
    }

    public function enabledBy(): ?UserId
    {
        return $this->enabledBy;
    }

    public function dateEnabled(): DateTime
    {
        return $this->dateEnabled;
    }

    /**
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::nullOrKeyExists($data, 'enabledBy');
        Assertion::keyExists($data, 'dateEnabled');

        return new self(
            UserId::fromString($data['id']),
            isset($data['enabledBy']) ? UserId::fromString($data['enabledBy']) : null,
            DateTime::fromString($data['dateEnabled']),
        );
    }

    public function serialize(): array
    {
        return parent::serialize() + [
            'dateEnabled' => $this->dateEnabled->toString(),
            'enabledBy' => null === $this->enabledBy ? null : $this->enabledBy->toString(),
        ];
    }
}
