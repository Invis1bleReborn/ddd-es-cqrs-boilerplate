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

namespace IdentityAccess\Infrastructure\Access\Serializer\Normalizer;

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

        $docs = $this->addMissedUriPrefixes($docs);
        $docs = $this->fixEmptyServerLists($docs);
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

    protected function addMissedUriPrefixes(array $docs): array
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

    protected function fixEmptyServerLists(array $docs): array
    {
        $this->walkOperations($docs, function (string $uri, string $method, array $paths) use (&$docs): void {
            if (!isset($paths[$method]['servers']) || !empty($data[$method]['servers'])) {
                return;
            }

            $docs['paths'][$uri][$method]['servers'] = [['url' => '/', 'description' => '']];
        });

        return $docs;
    }

    protected function fixInvalidSecurityConfiguration(array $docs): array
    {
        $security = [];

        foreach ($docs['security'] as $k => $v) {
            $security[] = [
                $k => $v,
            ];
        }

        $docs['security'] = $security;

        $this->walkOperations($docs, function (string $uri, string $method, array $paths) use (&$docs): void {
            if (!empty($paths[$method]['security'])) {
                return;
            }

            unset($docs['paths'][$uri][$method]['security']);
        });

        return $docs;
    }

    protected function removeBadLinks(array $docs): array
    {
        $operationIndex = [];

        $this->walkOperations($docs, function (
            string $uri,
            string $method,
            array $paths
        ) use (&$operationIndex): void {
            $operationIndex[$paths[$method]['operationId']] = true;
        });

        $this->walkOperations($docs, function (
            string $uri,
            string $method,
            array $paths
        ) use (
            &$docs,
            $operationIndex
        ): void {
            if (empty($paths[$method]['responses'])) {
                return;
            }

            foreach ($paths[$method]['responses'] as $statusCode => $response) {
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
        $this->walkOperations($docs, function (string $uri, string $method, array $paths) use (&$docs): void {
            if (!isset($paths[$method]['responses'][404])) {
                return;
            }

            if (preg_match('#{.+}#', $uri)) {
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
            array $paths
        ) use (&$docs): void {
            $uri = $this->apiUriPrefix . $uri;

            foreach ($paths[$method]['responses'] ?? [] as $statusCode => $response) {
                $docs['paths'][$uri][$method]['responses'][$statusCode]['description'] = $response['description'];
            }

            foreach ($paths[$method]['parameters'] ?? [] as $i => $parameter) {
                $docs['paths'][$uri][$method]['parameters'][$i]['description'] = $parameter['description'];
            }

            if (!isset($paths[$method]['schema'])) {
                return;
            }

            $docs['paths'][$uri][$method]['requestBody']['description'] = $paths[$method]['schema']['description'];

            foreach ($docs['paths'][$uri][$method]['requestBody']['content'] as $type => $content) {
                preg_match('#^\#/components/schemas/(.+)$#', $content['schema']['$ref'], $matches);
                $docs['components']['schemas'][$matches[1]]['description'] = $paths[$method]['schema']['description'];
            }
        });

        return $docs;
    }

    protected function setRolesEnum(array $docs): array
    {
        $roles = Role::toArray();

        foreach (['User.RegisterUserRequest', 'User.ChangeRolesRequest'] as $schemaName) {
            foreach (['', '.jsonld'] as $formatSuffix) {
                $schemaKey = $schemaName . $formatSuffix;

                if (!isset($docs['components']['schemas'][$schemaKey])) {
                    continue;
                }

                $docs['components']['schemas'][$schemaKey]['properties']['roles']['items']['enum'] = $roles;
            }
        }

        return $docs;
    }

    protected function walkOperations(array $docs, callable $function): void
    {
        foreach ($docs['paths'] as $uri => $paths) {
            foreach (static::METHODS as $method) {
                if (!isset($paths[$method])) {
                    continue;
                }

                $function($uri, $method, $paths);
            }
        }
    }
}
