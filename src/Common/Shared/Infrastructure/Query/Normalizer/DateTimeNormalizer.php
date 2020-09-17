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

namespace Common\Shared\Infrastructure\Query\Normalizer;

use Common\Shared\Domain\ValueObject\DateTime;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer as NativeDateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class DateTImeNormalizer.
 */
class DateTimeNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    private NativeDateTimeNormalizer $nativeDateTimeNormalizer;

    public function __construct(NativeDateTimeNormalizer $nativeDateTimeNormalizer)
    {
        $this->nativeDateTimeNormalizer = $nativeDateTimeNormalizer;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return $this->nativeDateTimeNormalizer->hasCacheableSupportsMethod();
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $dateTime = $this->nativeDateTimeNormalizer->denormalize($data, $type, $format, $context);

        return DateTime::fromString('@' . $dateTime->format('U.u'));
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $this->nativeDateTimeNormalizer->supportsDenormalization($data, $type, $format);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof DateTime) {
            throw new InvalidArgumentException(sprintf('The object must be instance of the "%s".', DateTime::class));
        }

        return $this->nativeDateTimeNormalizer->normalize($object->toNative(), $format, $context);
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof DateTime &&
            $this->nativeDateTimeNormalizer->supportsNormalization($data->toNative(), $format);
    }
}
