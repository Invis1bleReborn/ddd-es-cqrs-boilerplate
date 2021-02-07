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
use ApiPlatform\Core\OpenApi\Model\Link;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\Model\Response;
use ApiPlatform\Core\OpenApi\OpenApi;

/**
 * Class BadLinksRemoverTest.
 */
class BadLinksRemoverTest extends OpenApiDecoratorTestCase
{
    /**
     * @test
     * @dataProvider providePathsWithBadLinks
     */
    public function itRemovesBadLinks(Paths $paths, Paths $expectedPaths): void
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

        $postItemLinks = new \ArrayObject();
        $postItemLinks['GetItem'] = new Link('getItem');

        $putItemLinks = new \ArrayObject();
        $putItemLinks['GetItem'] = new Link('getItem');
        $putItemLinks['GetAnotherItem'] = new Link('getAnotherItem');

        $paths->addPath('/path', new PathItem(
            null,
            null,
            null,
            null,
            new Operation('putItem', [], [
                204 => new Response('[204] Item updated.', null, null, $putItemLinks),
            ]),
            new Operation('postItem', [], [
                200 => new Response('[200] Item created.'),
                201 => new Response('[201] Item created.', null, null, $postItemLinks),
            ])
        ));

        $paths->addPath('/another_path', new PathItem(
            null,
            null,
            null,
            new Operation('getAnotherItem', [], [
                200 => new Response('[200] The Item.'),
            ])
        ));

        $expectedPaths = new Paths();

        $expectedPutItemLinks = new \ArrayObject();
        $expectedPutItemLinks['GetAnotherItem'] = new Link('getAnotherItem');

        $expectedPaths->addPath('/path', new PathItem(
            null,
            null,
            null,
            null,
            new Operation('putItem', [], [
                204 => new Response('[204] Item updated.', null, null, $expectedPutItemLinks),
            ]),
            new Operation('postItem', [], [
                200 => new Response('[200] Item created.'),
                201 => new Response('[201] Item created.'),
            ])
        ));

        $expectedPaths->addPath('/another_path', new PathItem(
            null,
            null,
            null,
            new Operation('getAnotherItem', [], [
                200 => new Response('[200] The Item.'),
            ])
        ));

        yield [$paths, $expectedPaths];
    }

    protected function createDecorator(): OpenApiDecorator
    {
        return new BadLinksRemover($this->openApiFactoryStub);
    }
}
