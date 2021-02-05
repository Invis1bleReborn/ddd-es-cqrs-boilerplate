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

use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\Model\Response;
use ApiPlatform\Core\OpenApi\OpenApi;

/**
 * Class BadLinksRemover.
 */
class EmptyResponseContentNullifier extends OpenApiFactoryDecorator
{
    use SetPropertyValueTrait;
    use WalkResponsesTrait;

    protected function decorate(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths = $this->nullifyEmptyResponseContent($paths);

        return $openApi->withPaths($paths);
    }

    protected function nullifyEmptyResponseContent(Paths $paths): Paths
    {
        $this->walkResponses($paths, function (int $statusCode, Response $response): void {
            if (204 !== $statusCode) {
                return;
            }

            $content = $response->getContent();

            if (null === $content) {
                return;
            }

            $this->setPropertyValue($response, 'content', null);
        });

        return $paths;
    }
}
