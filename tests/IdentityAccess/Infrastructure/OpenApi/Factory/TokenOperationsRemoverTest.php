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

use ApiPlatform\Core\OpenApi\Model\Info;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\OpenApi;
use Common\Shared\Infrastructure\OpenApi\Factory\ApiUriPrefixAwareDecoratorTestCase;
use Common\Shared\Infrastructure\OpenApi\Factory\OpenApiDecorator;

/**
 * Class TokenOperationsRemoverTest.
 */
class TokenOperationsRemoverTest extends ApiUriPrefixAwareDecoratorTestCase
{
    /**
     * @test
     * @dataProvider providePathsWithTokenOperations
     */
    public function itRemovesTokenOperations(Paths $paths, Paths $expectedPaths): void
    {
        $openApi = new OpenApi(
            new Info('API factory test', '0.0.1'),
            [],
            $paths
        );

        $this->openApiFactoryStub->method('__invoke')
            ->willReturn($openApi);

        $this->assertEquals(
            $openApi->withPaths($expectedPaths),
            $this->decorator->__invoke([])
        );
    }

    /**
     * @return iterable<Paths[]>
     */
    public function providePathsWithTokenOperations(): iterable
    {
        $paths = new Paths();

        $paths->addPath(self::URI_PREFIX . '/tokens', new PathItem(
            null,
            null,
            null,
            null,
            null,
            new Operation('createToken')
        ));

        $paths->addPath(self::URI_PREFIX . '/tokens/{id}', new PathItem(
            null,
            null,
            null,
            new Operation('getToken')
        ));

        $expectedPaths = new Paths();

        $expectedPaths->addPath(self::URI_PREFIX . '/tokens', new PathItem(
            null,
            null,
            null,
            null,
            null,
            new Operation('createToken')
        ));

        yield [$paths, $expectedPaths];
    }

    protected function createDecorator(): OpenApiDecorator
    {
        return new TokenOperationsRemover($this->openApiFactoryStub, self::URI_PREFIX);
    }
}
