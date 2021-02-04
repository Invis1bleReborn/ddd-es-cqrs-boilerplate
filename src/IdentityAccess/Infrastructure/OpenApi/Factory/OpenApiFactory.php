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
use ApiPlatform\Core\OpenApi\Model\Components;
use ApiPlatform\Core\OpenApi\Model\Link;
use ApiPlatform\Core\OpenApi\Model\MediaType;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Paths;
use ApiPlatform\Core\OpenApi\Model\Response;
use ApiPlatform\Core\OpenApi\OpenApi;
use Common\Shared\Infrastructure\OpenApi\Iterator\Component\Schema\PropertiesIterator;
use Common\Shared\Infrastructure\OpenApi\Iterator\Component\SchemasIterator;
use Common\Shared\Infrastructure\OpenApi\Iterator\PathItem\Operation\ResponsesIterator;
use Common\Shared\Infrastructure\OpenApi\Iterator\PathItem\OperationsIterator;
use Common\Shared\Infrastructure\OpenApi\Iterator\PathsIterator;
use IdentityAccess\Domain\Access\ValueObject\Role;

/**
 * Class OpenApiFactory.
 */
class OpenApiFactory implements OpenApiFactoryInterface
{
    protected OpenApiFactoryInterface $decorated;

    protected array $patchData;

    protected string $apiUriPrefix;

    public function __construct(
        OpenApiFactoryInterface $decorated,
        array $patchData,
        string $apiUriPrefix = '/api'
    ) {
        $this->decorated = $decorated;
        $this->patchData = $patchData;
        $this->apiUriPrefix = $apiUriPrefix;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $paths = $openApi->getPaths();
        $paths = $this->addMissingUriPrefixes($paths);
        $paths = $this->removeTokenOperations($paths);
        $paths = $this->removeBadLinks($paths);
        $paths = $this->nullifyEmptyResponseContent($paths);

        $openApi = $openApi->withPaths($paths);

        return $this->applyPatch($openApi)
            ->withComponents($this->setRolesEnum($openApi->getComponents()));
    }

    protected function addMissingUriPrefixes(Paths $paths): Paths
    {
        return $this->mapPaths($paths, function (PathItem $pathItem, string $uri): array {
            if (0 !== strpos($uri, $this->apiUriPrefix . '/')) {
                $uri = $this->apiUriPrefix . $uri;
            }

            return [$pathItem, $uri];
        });
    }

    protected function removeTokenOperations(Paths $paths): Paths
    {
        return $this->mapPaths($paths, function (PathItem $pathItem, string $uri): ?array {
            if ($this->apiUriPrefix . '/tokens/{id}' === $uri) {
                return null;
            }

            return [$pathItem, $uri];
        });
    }

    protected function removeBadLinks(Paths $paths): Paths
    {
        $operationIndex = [];

        $this->walkOperations($paths, function (Operation $operation) use (&$operationIndex): void {
            $operationIndex[$operation->getOperationId()] = true;
        });

        $this->walkResponses($paths, function (int $statusCode, Response $response): void {
            if (204 === $statusCode) {
                return;
            }

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

    protected function applyPatch(OpenApi $openApi): OpenApi
    {
        $uriPrefixLength = strlen($this->apiUriPrefix);
        $schemas = $openApi->getComponents()->getSchemas();

        $this->walkOperations($openApi->getPaths(), function (
            Operation $operation,
            string $uri,
            string $method
        ) use (
            $uriPrefixLength,
            $schemas
        ): void {
            $uri = substr($uri, $uriPrefixLength);

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

            foreach ($requestBody->getContent() as $type => $content) {
                /* @var MediaType $content */
                preg_match('#^\#/components/schemas/(.+)$#', $content->getSchema()['$ref'], $matches);
                $schemas[$matches[1]]['description'] = $this->patchData[$uri][$method]['schema']['description'];
            }
        });
        
        return $openApi;
    }

    protected function setRolesEnum(Components $components): Components
    {
        $roles = Role::toArray();

        foreach (new SchemasIterator($components) as $schemaName => $schema) {
            foreach (new PropertiesIterator($schema) as $propertyName => $property) {
                if ('array' !== $property['type'] ||
                    !isset($property['description']) ||
                    'User roles.' !== $property['description']
                ) {
                    continue;
                }

                $property['items']['enum'] = $roles;
            }
        }

        return $components;
    }

    protected function mapPaths(Paths $paths, callable $function): Paths
    {
        $mappedPaths = new Paths();

        $this->walkPaths($paths, function (PathItem $pathItem, string $uri) use ($mappedPaths, $function): void {
            $mapped = $function($pathItem, $uri);

            if (null === $mapped) {
                return;
            }

            $mappedPaths->addPath($mapped[1], $mapped[0]);
        });

        return $mappedPaths;
    }

    protected function walkResponses(Paths $paths, callable $function): void
    {
        $this->walkOperations($paths, function (Operation $operation) use ($function): void {
            foreach (new ResponsesIterator($operation) as $statusCode => $response) {
                $function($statusCode, $response);
            }
        });
    }

    protected function walkOperations(Paths $paths, callable $function): void
    {
        $this->walkPaths($paths, function (PathItem $pathItem, string $uri) use ($function): void {
            foreach (new OperationsIterator($pathItem) as $httpMethod => $operation) {
                $function($operation, $uri, $httpMethod);
            }
        });
    }
    
    protected function walkPaths(Paths $paths, callable $function): void
    {
        foreach (new PathsIterator($paths) as $uri => $pathItem) {
            $function($pathItem, $uri);
        }
    }

    private function setPropertyValue(object $object, string $property, $value): void
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
        $reflection->setAccessible(false);
    }
}
