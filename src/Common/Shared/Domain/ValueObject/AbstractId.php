<?php

declare(strict_types=1);

namespace Common\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;

/**
 * Class AbstractId
 *
 * @package Common\Shared\Domain\ValueObject
 */
abstract class AbstractId implements IdInterface
{
    protected string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @return static
     * @throws AssertionFailedException
     */
    public static function fromString(string $value)
    {
        Assertion::uuid($value);

        return new static($value);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toBytes(): string
    {
        return Uuid::fromString($this->value)
            ->getBytes();
    }

    /**
     * @param IdInterface $id
     *
     * @return bool
     */
    public function equals(IdInterface $id): bool
    {
        return $this->value === (string)$id && get_class($this) === get_class($id);
    }

}
