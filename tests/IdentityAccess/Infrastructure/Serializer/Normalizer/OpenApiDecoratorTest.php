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

namespace IdentityAccess\Infrastructure\Serializer\Normalizer;

use IdentityAccess\Domain\Access\ValueObject\Role;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class OpenApiDecoratorTest.
 */
class OpenApiDecoratorTest extends TestCase
{
    private const URI_PREFIX = '/api';

    private ?OpenApiDecorator $openApiDecorator;

    private array $patchData = [
        '/path' => [
            'get' => [
                'responses' => [
                    200 => [
                        'description' => 'Response description from patch.',
                    ],
                ],
            ],
        ],
        '/path/{id}' => [
            'put' => [
                'schema' => [
                    'description' => 'Schema description from patch.',
                ],
                'parameters' => [
                    [
                        'description' => 'Parameter description from patch.',
                    ],
                ],
            ],
        ],
    ];

    public function testNormalize(): void
    {
        $decorated = $this->openApiDecorator->normalize([
            'paths' => [
                '/path' => [
                    'get' => [
                        'operationId' => 'someOperationId',
                        'responses' => [
                            200 => [],
                            404 => [
                                'description' => 'Not found.',
                            ],
                        ],
                    ],
                    'put' => [
                        'operationId' => 'anotherOperationId',
                        'responses' => [
                            204 => [
                                'description' => 'Some description.',
                                'content' => [],
                                'links' => [
                                    'foo' => 'nonExistingOperationId',
                                ],
                            ],
                            404 => [
                                'description' => 'Not found.',
                            ],
                        ],
                    ],
                ],
                '/path/{id}' => [
                    'put' => [
                        'operationId' => 'yetAnotherOperationId',
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/YetAnotherOperation',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            404 => [
                                'description' => 'Not found.',
                            ],
                        ],
                        'parameters' => [
                            [
                                'name' => 'id',
                            ],
                        ],
                    ],
                ],
                '/tokens/{id}' => [],
            ],
            'components' => [
                'schemas' => [
                    'anotherOperation' => [
                        'type' => 'object',
                        'properties' => [
                            'foo' => [
                                'type' => 'array',
                                'description' => 'User roles.',
                                'items' => [],
                            ],
                        ],
                    ],
                    'YetAnotherOperation' => [
                        'type' => 'object',
                    ],
                ],
            ],
            'security' => [
                'apiKey' => [],
            ],
        ]);

        $this->assertSame([
            'paths' => [
                static::URI_PREFIX . '/path' => [
                    'get' => [
                        'operationId' => 'someOperationId',
                        'responses' => [
                            200 => [
                                'description' => $this->patchData['/path']['get']['responses'][200]['description'],
                            ],
                        ],
                    ],
                    'put' => [
                        'operationId' => 'anotherOperationId',
                        'responses' => [
                            204 => [
                                'description' => 'Some description.',
                            ],
                        ],
                    ],
                ],
                static::URI_PREFIX . '/path/{id}' => [
                    'put' => [
                        'operationId' => 'yetAnotherOperationId',
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/YetAnotherOperation',
                                    ],
                                ],
                            ],
                            'description' => $this->patchData['/path/{id}']['put']['schema']['description'],
                        ],
                        'responses' => [
                            404 => [
                                'description' => 'Not found.',
                            ],
                        ],
                        'parameters' => [
                            [
                                'name' => 'id',
                                'description' => $this->patchData['/path/{id}']['put']['parameters'][0]['description'],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'schemas' => [
                    'anotherOperation' => [
                        'type' => 'object',
                        'properties' => [
                            'foo' => [
                                'type' => 'array',
                                'description' => 'User roles.',
                                'items' => [
                                    'enum' => Role::toArray(),
                                ],
                            ],
                        ],
                    ],
                    'YetAnotherOperation' => [
                        'type' => 'object',
                        'description' => $this->patchData['/path/{id}']['put']['schema']['description'],
                    ],
                ],
            ],
            'security' => [
                ['apiKey' => []],
            ],
        ], $decorated);
    }

    protected function setUp(): void
    {
        $normalizerStub = $this->createStub(NormalizerInterface::class);

        $normalizerStub->method('supportsNormalization')
            ->willReturn(true);

        $normalizerStub->method('normalize')
            ->willReturnArgument(0);

        $this->openApiDecorator = new OpenApiDecorator($normalizerStub, $this->patchData, static::URI_PREFIX);
    }
}
