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

/**
 * Class ApiUriPrefixAwareDecorator.
 */
abstract class ApiUriPrefixAwareDecorator extends OpenApiFactoryDecorator
{
    protected string $apiUriPrefix;

    public function __construct(OpenApiFactoryInterface $decorated, string $apiUriPrefix = '/api')
    {
        parent::__construct($decorated);

        $this->apiUriPrefix = $apiUriPrefix;
    }
}
