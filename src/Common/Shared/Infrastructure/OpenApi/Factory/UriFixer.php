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
use ApiPlatform\Core\OpenApi\OpenApi;
use Common\Shared\Infrastructure\OpenApi\Iterator\PathsIterator;

/**
 * Class UriFixer.
 */
class UriFixer extends ApiUriPrefixAwareDecorator
{
    protected function decorate(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths = $this->addMissingUriPrefixes($paths);

        return $openApi->withPaths($paths);
    }

    protected function addMissingUriPrefixes(Paths $paths): Paths
    {
        $fixedPaths = new Paths();

        foreach (new PathsIterator($paths) as $uri => $item) {
            if (0 !== strpos($uri, $this->apiUriPrefix . '/')) {
                $uri = $this->apiUriPrefix . $uri;
            }

            $fixedPaths->addPath($uri, $item);
        }

        return $fixedPaths;
    }
}
