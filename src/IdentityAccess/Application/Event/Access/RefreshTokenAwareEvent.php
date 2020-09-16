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
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use Common\Shared\Application\Bus\Event\EventInterface;
use Common\Shared\Domain\Exception\DateTimeException;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Domain\Access\ValueObject\RefreshToken;

/**
 * Class RefreshTokenAwareEvent
 *
 * @package IdentityAccess\Application\Event\Access
 */
abstract class RefreshTokenAwareEvent implements EventInterface, Serializable
{
    protected RefreshToken $refreshToken;

    protected DateTime $refreshTokenDateExpired;

    public function __construct(
        RefreshToken $refreshToken,
        DateTime $refreshTokenDateExpired
    )
    {
        $this->refreshToken = $refreshToken;
        $this->refreshTokenDateExpired = $refreshTokenDateExpired;
    }

    public function refreshToken(): RefreshToken
    {
        return $this->refreshToken;
    }

    public function refreshTokenDateExpired(): DateTime
    {
        return $this->refreshTokenDateExpired;
    }

    /**
     * @param array $data
     *
     * @return $this
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data)
    {
        Assertion::keyExists($data, 'refreshToken');
        Assertion::keyExists($data, 'refreshTokenDateExpired');

        return new static(
            RefreshToken::fromString($data['refreshToken']),
            DateTime::fromString($data['refreshTokenDateExpired'])
        );
    }

    public function serialize(): array
    {
        return [
            'refreshToken' => $this->refreshToken->toString(),
            'refreshTokenDateExpired' => $this->refreshTokenDateExpired->toString(),
        ];
    }

}
