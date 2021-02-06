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

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Info;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\OpenApi;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\TestCase;

/**
 * Class UriFixerTest.
 */
class UriFixerTest extends TestCase
{
    private const URI_PREFIX = '/prefix';

    /**
     * @var OpenApiFactoryInterface|InvocationMocker|null
     */
    private ?OpenApiFactoryInterface $openApiFactoryStub;

    /**
     * @var UriFixer|null
     */
    private ?UriFixer $uriFixer;

    /**
     * @test
     * @dataProvider providePathsWithMissingUriPrefixes
     */
    public function itFixesMissingUriPrefixes(Paths $paths, Paths $expectedPaths): void
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
            $this->uriFixer->__invoke([])
        );
    }

    /**
     * @return iterable<Paths[]>
     */
    public function providePathsWithMissingUriPrefixes(): iterable
    {
        $paths = new Paths();

        $paths->addPath('/path_without_prefix', new PathItem(
            null,
            null,
            null,
            new Operation('getItem')
        ));

        $paths->addPath(self::URI_PREFIX . '/path_with_prefix', new PathItem(
            null,
            null,
            null,
            new Operation('getAnotherItem')
        ));

        $expectedPaths = new Paths();

        $expectedPaths->addPath(self::URI_PREFIX . '/path_without_prefix', new PathItem(
            null,
            null,
            null,
            new Operation('getItem')
        ));

        $expectedPaths->addPath(self::URI_PREFIX . '/path_with_prefix', new PathItem(
            null,
            null,
            null,
            new Operation('getAnotherItem')
        ));

        yield [$paths, $expectedPaths];
    }

    protected function setUp(): void
    {
        $this->openApiFactoryStub = $this->createStub(OpenApiFactoryInterface::class);
        $this->uriFixer = new UriFixer($this->openApiFactoryStub, self::URI_PREFIX);
    }
}
