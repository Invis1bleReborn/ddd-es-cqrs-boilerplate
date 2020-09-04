<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * Class HashedPassword
 *
 * @package IdentityAccess\Domain\Identity\ValueObject
 */
final class HashedPassword
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @return self
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
