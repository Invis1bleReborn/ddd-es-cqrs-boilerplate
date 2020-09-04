<?php

declare(strict_types=1);

namespace Common\Shared\Domain\ValueObject;

use Common\Shared\Domain\Exception\DateTimeException;

/**
 * Class DateTime
 *
 * @package Common\Shared\Domain\ValueObject
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
     * @param string $dateTime
     *
     * @return self
     * @throws DateTimeException
     */
    public static function fromString(string $dateTime): self
    {
        return self::create($dateTime);
    }

    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return self
     * @throws DateTimeException
     */
    public static function fromNative(\DateTimeInterface $dateTime): self
    {
        return self::create('@' . $dateTime->format('U.u'));
    }

    public function __toString(): string
    {
        return $this->value->format(self::FORMAT);
    }

    public function toString(): string
    {
        return $this->value->format(self::FORMAT);
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
     * @param string|null $value
     *
     * @return self
     * @throws DateTimeException
     */
    private static function create(string $value = null): self
    {
        try {
            return new self(\DateTimeImmutable::createFromFormat(
                'U.u',
                $value ?? sprintf('%.6F', microtime(true)),
                new \DateTimeZone('UTC')
            ));
        } catch (\Exception $e) {
            throw new DateTimeException($e);
        }
    }

}
