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

namespace IdentityAccess\Infrastructure\Access\Serializer\Normalizer;

use ApiPlatform\Core\Documentation\Documentation;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class SwaggerDecorator
 *
 */
class SwaggerDecorator implements NormalizerInterface
{
    private NormalizerInterface $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        unset($docs['paths']['/api/tokens/{id}']);

        return $docs;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Documentation;
    }
}
