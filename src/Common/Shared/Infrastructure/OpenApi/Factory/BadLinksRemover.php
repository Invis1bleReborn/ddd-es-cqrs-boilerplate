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

use ApiPlatform\Core\OpenApi\Model\Link;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\Model\Response;
use ApiPlatform\Core\OpenApi\OpenApi;

/**
 * Class BadLinksRemover.
 */
class BadLinksRemover extends OpenApiDecorator
{
    use SetPropertyValueTrait;
    use WalkResponsesTrait;

    protected function decorate(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths = $this->removeBadLinks($paths);

        return $openApi->withPaths($paths);
    }

    protected function removeBadLinks(Paths $paths): Paths
    {
        $operationIndex = [];

        $this->walkOperations($paths, function (Operation $operation) use (&$operationIndex): void {
            $operationIndex[$operation->getOperationId()] = true;
        });

        $this->walkResponses($paths, function (int $statusCode, Response $response): void {
            $links = $response->getLinks();

            if (null === $links || 0 === $links->count()) {
                return;
            }

            $fixedLinkList = [];

            foreach ($links as $name => $link) {
                /* @var Link $link */
                if (!isset($operationIndex[$link->getOperationId()])) {
                    continue;
                }

                $fixedLinkList[$name] = $link;
            }

            if (empty($fixedLinkList)) {
                $this->setPropertyValue($response, 'links', null);
            } else {
                $links->exchangeArray($fixedLinkList);
            }
        });

        return $paths;
    }
}
