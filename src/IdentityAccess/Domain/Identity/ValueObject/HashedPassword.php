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

namespace IdentityAccess\Domain\Identity\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * Class HashedPassword
 */
final class HashedPassword
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
        Assertion::minLength($value, 1, 'Invalid hashed password.');

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

    public function equals(self $hashedPassword): bool
    {
        return $hashedPassword->value === $this->value;
    }
}
