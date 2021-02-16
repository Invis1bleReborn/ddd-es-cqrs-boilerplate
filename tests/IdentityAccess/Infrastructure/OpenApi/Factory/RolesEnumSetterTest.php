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
use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\OpenApi;
use Common\Shared\Infrastructure\OpenApi\Factory\OpenApiDecorator;
use Common\Shared\Infrastructure\OpenApi\Factory\OpenApiDecoratorTestCase;
use IdentityAccess\Domain\Access\ValueObject\Role;

/**
 * Class RolesEnumSetterTest.
 */
class RolesEnumSetterTest extends OpenApiDecoratorTestCase
{
    /**
     * @test
     * @dataProvider provideComponentsWithRoles
     */
    public function itSetRolesEnum(Components $components, Components $expectedComponents): void
    {
        $openApi = new OpenApi(
            new Info('API factory test', '0.0.1'),
            [],
            new Paths(),
            $components
        );

        $this->openApiFactoryStub->method('__invoke')
            ->willReturn($openApi);

        $this->assertEquals(
            $openApi->withComponents($expectedComponents),
            $this->decorator->__invoke([])
        );
    }

    /**
     * @return iterable<Components[]>
     */
    public function provideComponentsWithRoles(): iterable
    {
        $schemas = new \ArrayObject();
        
        $schemas['User.ChangeRolesRequest'] = new \ArrayObject();
        $schemas['User.ChangeRolesRequest']['properties'] = ['roles' => new \ArrayObject()];
        $schemas['User.ChangeRolesRequest']['properties']['roles']['type'] = 'array';
        $schemas['User.ChangeRolesRequest']['properties']['roles']['description'] = 'User roles.';
        $schemas['User.ChangeRolesRequest']['properties']['roles']['items'] = [];
        
        $schemas['Foo.BarRequest'] = new \ArrayObject();
        $schemas['Foo.BarRequest']['properties'] = ['roles' => new \ArrayObject()];
        $schemas['Foo.BarRequest']['properties']['roles']['type'] = 'string';
        $schemas['Foo.BarRequest']['properties']['roles']['description'] = 'User roles.';
        $schemas['Foo.BarRequest']['properties']['roles']['items'] = [];

        $expectedSchemas = new \ArrayObject();

        $expectedSchemas['User.ChangeRolesRequest'] = new \ArrayObject();
        $expectedSchemas['User.ChangeRolesRequest']['properties'] = ['roles' => new \ArrayObject()];
        $expectedSchemas['User.ChangeRolesRequest']['properties']['roles']['type'] = 'array';
        $expectedSchemas['User.ChangeRolesRequest']['properties']['roles']['description'] = 'User roles.';
        $expectedSchemas['User.ChangeRolesRequest']['properties']['roles']['items'] = [
            'enum' => Role::toArray(),
        ];

        $expectedSchemas['Foo.BarRequest'] = new \ArrayObject();
        $expectedSchemas['Foo.BarRequest']['properties'] = ['roles' => new \ArrayObject()];
        $expectedSchemas['Foo.BarRequest']['properties']['roles']['type'] = 'string';
        $expectedSchemas['Foo.BarRequest']['properties']['roles']['description'] = 'User roles.';
        $expectedSchemas['Foo.BarRequest']['properties']['roles']['items'] = [];

        yield [new Components($schemas), new Components($expectedSchemas)];
    }

    protected function createDecorator(): OpenApiDecorator
    {
        return new RolesEnumSetter($this->openApiFactoryStub);
    }
}
