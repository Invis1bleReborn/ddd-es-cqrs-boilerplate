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

namespace IdentityAccess\Infrastructure\OpenApi\Factory;

use ApiPlatform\Core\OpenApi\Model\Components;
use ApiPlatform\Core\OpenApi\Model\Info;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\Parameter;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\Model\Response;
use ApiPlatform\Core\OpenApi\OpenApi;
use Common\Shared\Infrastructure\OpenApi\Factory\ApiUriPrefixAwareDecoratorTestCase;
use Common\Shared\Infrastructure\OpenApi\Factory\OpenApiDecorator;

/**
 * Class PatchApplierTest.
 */
class PatchApplierTest extends ApiUriPrefixAwareDecoratorTestCase
{
    /**
     * @test
     * @dataProvider providePatchApplicableApi
     */
    public function itAppliesPatch(OpenApi $openApi, OpenApi $expectedOpenApi): void
    {
        $this->openApiFactoryStub->method('__invoke')
            ->willReturn($openApi);

        $this->assertEquals(
            $expectedOpenApi,
            $this->decorator->__invoke([])
        );
    }

    /**
     * @return iterable<OpenApi[]>
     */
    public function providePatchApplicableApi(): iterable
    {
        $paths = new Paths();
        $paths->addPath(self::URI_PREFIX . '/path', new PathItem(
            null,
            null,
            null,
            new Operation('getItems', [], [
                200 => new Response(),
            ]),
        ));

        $paths->addPath(self::URI_PREFIX . '/path/{id}', new PathItem(
            null,
            null,
            null,
            null,
            new Operation('putItem', [], [], '', '', null, [
                new Parameter('id', 'path'),
            ], new RequestBody()),
        ));

        $openApi = new OpenApi(
            new Info('API factory test', '0.0.1'),
            [],
            $paths,
            new Components()
        );

        $expectedPaths = new Paths();
        $expectedPaths->addPath(self::URI_PREFIX . '/path', new PathItem(
            null,
            null,
            null,
            new Operation('getItems', [], [
                200 => new Response('Response description from patch.'),
            ])
        ));

        $expectedPaths->addPath(self::URI_PREFIX . '/path/{id}', new PathItem(
            null,
            null,
            null,
            null,
            new Operation('putItem', [], [], '', '', null, [
                new Parameter('id', 'path', 'Parameter description from patch.'),
            ], new RequestBody('Schema description from patch.')),
        ));

        $expectedOpenApi = new OpenApi(
            new Info('API factory test', '0.0.1'),
            [],
            $expectedPaths,
            new Components()
        );

        yield [$openApi, $expectedOpenApi];
    }

    protected function createDecorator(): OpenApiDecorator
    {
        return new PatchApplier($this->openApiFactoryStub, [
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
        ], self::URI_PREFIX);
    }
}
