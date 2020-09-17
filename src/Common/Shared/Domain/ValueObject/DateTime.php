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

namespace Common\Shared\Domain\ValueObject;

use Common\Shared\Domain\Exception\DateTimeException;

/**
 * Class DateTime
 */
class DateTime
{
    public const FORMAT = 'Y-m-d\TH:i:s.uP';

    private \DateTimeImmutable $value;

    private function __construct(\DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public static function now(): self
    {
        return self::create();
    }

    /**
     * @throws DateTimeException
     */
    public static function fromString(string $dateTime): self
    {
        return self::create($dateTime);
    }

    /**
     * @throws DateTimeException
     */
    public static function fromNative(\DateTimeInterface $dateTime): self
    {
        return self::create('@' . $dateTime->format('U.u'));
    }

    public function __toString(): string
    {
        return $this->value->format(static::FORMAT);
    }

    public function toString(): string
    {
        return $this->value->format(static::FORMAT);
    }

    public function toSeconds(): string
    {
        return $this->value->format('U.u');
    }

    public function toNative(string $fqcn = \DateTimeImmutable::class): \DateTimeInterface
    {
        if (!is_subclass_of($fqcn, \DateTimeInterface::class)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected instance of %s, instance of %s given.',
                \DateTimeInterface::class,
                $fqcn
            ));
        }

        if (\DateTimeImmutable::class === $fqcn) {
            return $this->value;
        }

        return new $fqcn('@' . $this->value->format('U.u'));
    }

    /**
     * @throws DateTimeException
     */
    private static function create(string $value = null): self
    {
        if (null === $value) {
            $value = sprintf('@%.6F', microtime(true));
            $timezone = new \DateTimeZone('UTC');
        } else {
            $timezone = null;
        }

        try {
            return new self(new \DateTimeImmutable(
                $value,
                $timezone
            ));
        } catch (\Exception $e) {
            throw new DateTimeException($e);
        }
    }
}
