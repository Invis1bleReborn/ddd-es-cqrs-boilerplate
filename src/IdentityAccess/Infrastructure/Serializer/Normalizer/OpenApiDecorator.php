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

namespace IdentityAccess\Infrastructure\Serializer\Normalizer;

use IdentityAccess\Domain\Access\ValueObject\Role;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class OpenApiDecorator.
 */
class OpenApiDecorator implements NormalizerInterface
{
    protected NormalizerInterface $decorated;

    protected array $patchData;

    protected ?string $apiUriPrefix;

    protected const METHODS = ['get', 'post', 'put', 'patch', 'delete', 'head', 'trace'];

    public function __construct(
        NormalizerInterface $decorated,
        array $patchData,
        string $apiUriPrefix = null
    ) {
        $this->decorated = $decorated;
        $this->patchData = $patchData;
        $this->apiUriPrefix = $apiUriPrefix ?? '/api';
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $docs = $this->addMissingUriPrefixes($docs);
        $docs = $this->fixInvalidSecurityConfiguration($docs);
        $docs = $this->removeTokenOperations($docs);
        $docs = $this->removeBadLinks($docs);
        $docs = $this->removeInvalidResponseContent($docs);
        $docs = $this->removeInvalid404Responses($docs);
        $docs = $this->applyPatch($docs);
        $docs = $this->setRolesEnum($docs);

        return $docs;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    protected function addMissingUriPrefixes(array $docs): array
    {
        $fixedPaths = [];

        foreach ($docs['paths'] as $uri => $paths) {
            if (false === strpos($uri, $this->apiUriPrefix)) {
                $uri = $this->apiUriPrefix . $uri;
            }

            $fixedPaths[$uri] = $paths;
        }

        $docs['paths'] = $fixedPaths;

        return $docs;
    }

    protected function removeTokenOperations(array $docs): array
    {
        unset($docs['paths'][$this->apiUriPrefix . '/tokens/{id}']);

        return $docs;
    }

    protected function fixInvalidSecurityConfiguration(array $docs): array
    {
        if (!isset($docs['security'])) {
            return $docs;
        }

        $security = [];

        foreach ($docs['security'] as $k => $v) {
            $security[] = [
                $k => $v,
            ];
        }

        $docs['security'] = $security;

        return $docs;
    }

    protected function removeBadLinks(array $docs): array
    {
        $operationIndex = [];

        $this->walkOperations($docs, function (
            string $uri,
            string $method,
            array $operation
        ) use (&$operationIndex): void {
            $operationIndex[$operation['operationId']] = true;
        });

        $this->walkOperations($docs, function (
            string $uri,
            string $method,
            array $operation
        ) use (
            &$docs,
            $operationIndex
        ): void {
            foreach ($operation['responses'] as $statusCode => $response) {
                $links = [];

                if (204 !== $statusCode && !empty($response['links'])) {
                    foreach ($response['links'] as $name => $link) {
                        if (!isset($operationIndex[$link['operationId']])) {
                            continue;
                        }

                        $links[$name] = $link;
                    }
                }

                if (empty($links)) {
                    unset($docs['paths'][$uri][$method]['responses'][$statusCode]['links']);
                } else {
                    $docs['paths'][$uri][$method]['responses'][$statusCode]['links'] = $links;
                }
            }
        });

        return $docs;
    }

    protected function removeInvalidResponseContent(array $docs): array
    {
        $this->walkOperations($docs, function (string $uri, string $method) use (&$docs): void {
            unset($docs['paths'][$uri][$method]['responses'][204]['content']);
        });

        return $docs;
    }

    protected function removeInvalid404Responses(array $docs): array
    {
        $this->walkOperations($docs, function (string $uri, string $method, array $operation) use (&$docs): void {
            if (!isset($operation['responses'][404]) || !empty($operation['parameters'])) {
                return;
            }

            unset($docs['paths'][$uri][$method]['responses'][404]);
        });

        return $docs;
    }

    protected function applyPatch(array $docs): array
    {
        $this->walkOperations(['paths' => $this->patchData], function (
            string $uri,
            string $method,
            array $operation
        ) use (&$docs): void {
            $uri = $this->apiUriPrefix . $uri;

            foreach ($operation['responses'] ?? [] as $statusCode => $response) {
                $docs['paths'][$uri][$method]['responses'][$statusCode]['description'] = $response['description'];
            }

            foreach ($operation['parameters'] ?? [] as $i => $parameter) {
                $docs['paths'][$uri][$method]['parameters'][$i]['description'] = $parameter['description'];
            }

            if (!isset($operation['schema'])) {
                return;
            }

            $docs['paths'][$uri][$method]['requestBody']['description'] = $operation['schema']['description'];

            foreach ($docs['paths'][$uri][$method]['requestBody']['content'] as $type => $content) {
                preg_match('#^\#/components/schemas/(.+)$#', $content['schema']['$ref'], $matches);
                $docs['components']['schemas'][$matches[1]]['description'] = $operation['schema']['description'];
            }
        });

        return $docs;
    }

    protected function setRolesEnum(array $docs): array
    {
        $roles = Role::toArray();

        foreach ($docs['components']['schemas'] as $schemaName => $schema) {
            foreach ($schema['properties'] ?? [] as $propertyName => $property) {
                if ('array' !== $property['type'] ||
                    !isset($property['description']) ||
                    'User roles.' !== $property['description']
                ) {
                    continue;
                }

                $docs['components']['schemas'][$schemaName]['properties'][$propertyName]['items']['enum'] = $roles;
            }
        }

        return $docs;
    }

    protected function walkPaths(array $docs, callable $function): void
    {
        foreach ($docs['paths'] as $uri => $path) {
            $function($uri, $path);
        }
    }

    protected function walkMethods(array $path, callable $function): void
    {
        foreach (static::METHODS as $method) {
            if (!isset($path[$method])) {
                continue;
            }

            $function($method, $path[$method]);
        }
    }

    protected function walkOperations(array $docs, callable $function): void
    {
        $this->walkPaths($docs, function (string $uri, array $path) use ($function): void {
            $this->walkMethods($path, function (string $method, array $operation) use ($uri, $function): void {
                $function($uri, $method, $operation);
            });
        });
    }
}
