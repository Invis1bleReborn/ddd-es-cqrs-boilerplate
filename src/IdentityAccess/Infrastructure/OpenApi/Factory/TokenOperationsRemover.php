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

use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\OpenApi;
use Common\Shared\Infrastructure\OpenApi\Factory\ApiUriPrefixAwareDecorator;
use Common\Shared\Infrastructure\OpenApi\Iterator\PathsIterator;

/**
 * Class TokenOperationsRemover.
 */
class TokenOperationsRemover extends ApiUriPrefixAwareDecorator
{
    protected function decorate(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths = $this->removeTokenOperations($paths);

        return $openApi->withPaths($paths);
    }

    protected function removeTokenOperations(Paths $paths): Paths
    {
        $fixedPaths = new Paths();

        foreach (new PathsIterator($paths) as $uri => $item) {
            if ($this->apiUriPrefix . '/tokens/{id}' === $uri) {
                continue;
            }

            $fixedPaths->addPath($uri, $item);
        }

        return $fixedPaths;
    }
}
