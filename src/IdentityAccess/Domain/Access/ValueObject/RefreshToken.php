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

namespace IdentityAccess\Domain\Access\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * Class RefreshToken
 */
final class RefreshToken
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $value): self
    {
        $minLength = 1;

        Assertion::minLength($value, $minLength, sprintf(
            'Refresh Token must be at least %s characters long.',
            $minLength
        ));

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
