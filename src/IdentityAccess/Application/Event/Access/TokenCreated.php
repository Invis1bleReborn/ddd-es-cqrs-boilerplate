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

use Assert\Assertion;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\ValueObject\RefreshToken;
use IdentityAccess\Domain\Identity\ValueObject\Username;

/**
 * Class TokenCreated.
 */
final class TokenCreated extends RefreshTokenAwareEvent
{
    private Username $username;

    public function __construct(
        RefreshToken $refreshToken,
        Username $username,
        DateTime $refreshTokenDateExpired
    )
    {
        parent::__construct($refreshToken, $refreshTokenDateExpired);

        $this->username = $username;
    }

    public function username(): Username
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'refreshToken');
        Assertion::keyExists($data, 'username');
        Assertion::keyExists($data, 'refreshTokenDateExpired');

        return new self(
            RefreshToken::fromString($data['refreshToken']),
            Username::fromString($data['username']),
            DateTime::fromString($data['refreshTokenDateExpired']),
        );
    }

    public function serialize(): array
    {
        return parent::serialize() + [
            'username' => $this->username->toString(),
        ];
    }
}
