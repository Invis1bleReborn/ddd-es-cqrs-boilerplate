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
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\TestCase;

/**
 * Class OpenApiDecoratorTestCase.
 */
abstract class OpenApiDecoratorTestCase extends TestCase
{
    /**
     * @var OpenApiFactoryInterface|InvocationMocker|null
     */
    protected ?OpenApiFactoryInterface $openApiFactoryStub;

    /**
     * @var OpenApiDecorator|null
     */
    protected ?OpenApiDecorator $decorator;

    protected function setUp(): void
    {
        $this->openApiFactoryStub = $this->createStub(OpenApiFactoryInterface::class);
        $this->decorator = $this->createDecorator();
    }

    abstract protected function createDecorator(): OpenApiDecorator;
}
