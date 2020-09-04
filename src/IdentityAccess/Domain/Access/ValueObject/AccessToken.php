<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Access\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * Class AccessToken
 *
 * @package IdentityAccess\Domain\Access\ValueObject
 */
final class AccessToken
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
        $minLength = 1;

        Assertion::minLength($value, $minLength, sprintf(
            'Access Token must be at least %s characters long.',
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
