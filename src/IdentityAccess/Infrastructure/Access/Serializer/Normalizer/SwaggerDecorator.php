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
use IdentityAccess\Ui\Access\CreateToken\CreateTokenRequest;
use IdentityAccess\Ui\Access\RefreshToken\RefreshTokenRequest;
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

        $patchData = [
            CreateTokenRequest::class => [
                'resource' => 'Token',
                'uri' => '/tokens',
                'responses' => [
                    201 => [
                        'description' => 'Token created.',
                    ],
                    401 => [
                        'description' => 'Bad credentials or Account disabled.',
                    ],
                ],
                'schema' => [
                    'description' => 'User account credentials.',
                    'name' => 'Token:Create',
                ],
            ],
            RefreshTokenRequest::class => [
                'resource' => 'Token',
                'uri' => '/refresh_tokens',
                'responses' => [
                    201 => [
                        'description' => 'Token refreshed.',
                    ],
                    401 => [
                        'description' => 'Refresh token does not exist.',
                    ],
                ],
                'schema' => [
                    'description' => 'Refresh token.',
                    'name' => 'Token:Refresh',
                ],
            ],
        ];

        foreach ($patchData as $inputClass => $data) {
            $uri = $this->apiUriPrefix . $data['uri'];

            foreach ($data['responses'] as $statusCode => $response) {
                $docs['paths'][$uri]['post']['responses'][$statusCode]['description'] = $response['description'];
            }

            $schemaName = $data['resource'] . ':' . md5($inputClass);

            $docs['components']['schemas'][$schemaName]['description'] =
            $docs['components']['schemas'][$schemaName . ':jsonld']['description'] =
            $docs['paths'][$uri]['post']['requestBody']['description'] = $data['schema']['description'];

            foreach ($docs['paths'][$uri]['post']['requestBody']['content'] as $type => $content) {
                $docs['paths'][$uri]['post']['requestBody']['content'][$type]['schema']['$ref'] = strtr(
                    $content['schema']['$ref'],
                    [$schemaName => $data['schema']['name']]
                );
            }

            $docs['components']['schemas'][$data['schema']['name']] = $docs['components']['schemas'][$schemaName];
            $docs['components']['schemas'][$data['schema']['name'] . ':jsonld'] =
                $docs['components']['schemas'][$schemaName . ':jsonld'];

            unset(
                $docs['paths'][$uri]['post']['responses'][201]['links'],
                $docs['paths'][$uri]['post']['responses'][404],
                $docs['components']['schemas'][$schemaName],
                $docs['components']['schemas'][$schemaName . ':jsonld']
            );
        }

        unset($docs['paths'][$this->apiUriPrefix . '/tokens/{id}']);

        return $docs;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Documentation;
    }
}
