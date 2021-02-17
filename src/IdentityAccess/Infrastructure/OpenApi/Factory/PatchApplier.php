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

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\MediaType;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\OpenApi;
use Common\Shared\Infrastructure\OpenApi\Factory\ApiUriPrefixAwareDecorator;
use Common\Shared\Infrastructure\OpenApi\Factory\SetPropertyValueTrait;
use Common\Shared\Infrastructure\OpenApi\Factory\WalkOperationsTrait;

/**
 * Class PatchApplier.
 */
class PatchApplier extends ApiUriPrefixAwareDecorator
{
    use SetPropertyValueTrait;
    use WalkOperationsTrait;

    protected array $patchData;

    public function __construct(
        OpenApiFactoryInterface $decorated,
        array $patchData,
        string $apiUriPrefix = '/api'
    ) {
        parent::__construct(
            $decorated,
            $apiUriPrefix
        );

        $this->patchData = $patchData;
    }

    protected function decorate(OpenApi $openApi): OpenApi
    {
        return $this->applyPatch($openApi);
    }

    protected function applyPatch(OpenApi $openApi): OpenApi
    {
        $components = $openApi->getComponents();

        if (null === $components) {
            $schemas = null;
        } else {
            $schemas = $components->getSchemas();
        }

        $this->walkOperations($openApi->getPaths(), function (
            Operation $operation,
            string $uri,
            string $method
        ) use ($schemas): void {
            $uri = preg_replace('#^' . preg_quote($this->apiUriPrefix, '#') . '#', '', $uri);

            if (isset($this->patchData[$uri][$method]['responses'])) {
                foreach ($operation->getResponses() as $statusCode => $response) {
                    if (!isset($this->patchData[$uri][$method]['responses'][$statusCode]['description'])) {
                        continue;
                    }

                    $this->setPropertyValue(
                        $response,
                        'description',
                        $this->patchData[$uri][$method]['responses'][$statusCode]['description']
                    );
                }
            }

            if (isset($this->patchData[$uri][$method]['parameters'])) {
                foreach ($operation->getParameters() as $i => $parameter) {
                    if (!isset($this->patchData[$uri][$method]['parameters'][$i]['description'])) {
                        continue;
                    }

                    $this->setPropertyValue(
                        $parameter,
                        'description',
                        $this->patchData[$uri][$method]['parameters'][$i]['description']
                    );
                }
            }

            $requestBody = $operation->getRequestBody();

            if (!isset($this->patchData[$uri][$method]['schema']['description'], $requestBody)) {
                return;
            }

            $this->setPropertyValue(
                $requestBody,
                'description',
                $this->patchData[$uri][$method]['schema']['description']
            );

            if (null === $schemas) {
                return;
            }

            foreach ($requestBody->getContent() as $type => $content) {
                /* @var MediaType $content */
                preg_match('#^\#/components/schemas/(.+)$#', $content->getSchema()['$ref'], $matches);
                $schemas[$matches[1]]['description'] = $this->patchData[$uri][$method]['schema']['description'];
            }
        });

        return $openApi;
    }
}
