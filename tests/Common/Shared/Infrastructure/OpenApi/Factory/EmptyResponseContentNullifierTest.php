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

namespace Common\Shared\Infrastructure\OpenApi\Factory;

use ApiPlatform\Core\OpenApi\Model\Info;
use ApiPlatform\Core\OpenApi\Model\MediaType;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\Model\Response;
use ApiPlatform\Core\OpenApi\OpenApi;

/**
 * Class EmptyResponseContentNullifierTest.
 */
class EmptyResponseContentNullifierTest extends OpenApiDecoratorTestCase
{
    /**
     * @test
     * @dataProvider providePathsWithBadLinks
     */
    public function itNullifiesEmptyResponseContent(Paths $paths, Paths $expectedPaths): void
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
    public function providePathsWithBadLinks(): iterable
    {
        $paths = new Paths();

        $putItemContent = new \ArrayObject();
        $putItemContent['application/json'] = new MediaType(new \ArrayObject());

        $postItemContent = new \ArrayObject();
        $postItemContent['application/json'] = new MediaType(new \ArrayObject());

        $paths->addPath('/path', new PathItem(
            null,
            null,
            null,
            null,
            new Operation('putItem', [], [
                200 => new Response('[200] Item updated.', $putItemContent),
                204 => new Response('[204] Item updated.', $putItemContent),
            ]),
            new Operation('postItem', [], [
                200 => new Response('[200] Item created.', $postItemContent),
            ])
        ));

        $expectedPaths = new Paths();

        $expectedPutItem200Content = new \ArrayObject();
        $expectedPutItem200Content['application/json'] = new MediaType(new \ArrayObject());

        $expectedPostItemContent = new \ArrayObject();
        $expectedPostItemContent['application/json'] = new MediaType(new \ArrayObject());

        $expectedPaths->addPath('/path', new PathItem(
            null,
            null,
            null,
            null,
            new Operation('putItem', [], [
                200 => new Response('[200] Item updated.', $expectedPutItem200Content),
                204 => new Response('[204] Item updated.'),
            ]),
            new Operation('postItem', [], [
                200 => new Response('[200] Item created.', $expectedPostItemContent),
            ])
        ));

        yield [$paths, $expectedPaths];
    }

    protected function createDecorator(): OpenApiDecorator
    {
        return new EmptyResponseContentNullifier($this->openApiFactoryStub);
    }
}
