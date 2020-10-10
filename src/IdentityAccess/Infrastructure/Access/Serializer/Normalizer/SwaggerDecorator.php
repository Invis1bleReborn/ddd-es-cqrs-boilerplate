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
 * Class SwaggerDecorator.
 */
class SwaggerDecorator implements NormalizerInterface
{
    private NormalizerInterface $decorated;

    private ?string $apiUriPrefix;

    public function __construct(NormalizerInterface $decorated, string $apiUriPrefix = null)
    {
        $this->decorated = $decorated;
        $this->apiUriPrefix = $apiUriPrefix ?? '/api';
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $docs['paths'][$this->apiUriPrefix . '/tokens']['post']['responses'][201]['description'] = 'Token created.';
        $docs['paths'][$this->apiUriPrefix . '/tokens']['post']['responses'][401] = [
            'description' => 'Bad credentials or Account disabled.',
        ];

        $docs['paths'][$this->apiUriPrefix . '/refresh_tokens']['post']['responses'][201]['description'] =
            'Token refreshed.';
        $docs['paths'][$this->apiUriPrefix . '/refresh_tokens']['post']['responses'][401] = [
            'description' => 'Refresh token does not exist.',
        ];

        unset(
            $docs['paths'][$this->apiUriPrefix . '/refresh_tokens']['post']['responses'][201]['links'],
            $docs['paths'][$this->apiUriPrefix . '/refresh_tokens']['post']['responses'][404],
            $docs['paths'][$this->apiUriPrefix . '/tokens']['post']['responses'][201]['links'],
            $docs['paths'][$this->apiUriPrefix . '/tokens']['post']['responses'][404],
            $docs['paths'][$this->apiUriPrefix . '/tokens/{id}']
        );

        return $docs;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Documentation;
    }
}
