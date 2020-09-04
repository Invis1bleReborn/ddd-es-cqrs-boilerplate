<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * Class Email
 *
 * @package IdentityAccess\Domain\Identity\ValueObject
 */
final class Email
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
        Assertion::email($value, 'Not a valid email.');

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

    public function equals(self $email): bool
    {
        return $email->value === $this->value;
    }

}
