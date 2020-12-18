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

namespace Common\Shared\Infrastructure\Query\Metadata;

use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;

/**
 * Class PhpModelFilterMetadataFactory.
 */
final class PhpModelFilterMetadataFactory implements ResourceMetadataFactoryInterface
{
    /**
     * @var array[] $filterDescriptors
     */
    private array $filterDescriptors;

    private ?ResourceMetadataFactoryInterface $decorated;

    public function __construct(array $filterDescriptors, ResourceMetadataFactoryInterface $decorated = null)
    {
        $this->decorated = $decorated;
        $this->filterDescriptors = $filterDescriptors;
    }

    public function create(string $modelClass): ResourceMetadata
    {
        $parentModelMetadata = null;
        if ($this->decorated) {
            try {
                $parentModelMetadata = $this->decorated->create($modelClass);
            } catch (ResourceClassNotFoundException $e) {
                // Ignore not found exception from decorated factories
            }
        }

        if (null === $parentModelMetadata) {
            return $this->handleNotFound($parentModelMetadata, $modelClass);
        }

        $filters = array_keys(array_filter(
            $this->filterDescriptors,
            fn (array $descriptor): bool => $descriptor['model_class'] === $modelClass
        ));

        if (!$filters) {
            return $parentModelMetadata;
        }

        $parentFilters = $parentModelMetadata->getAttribute('filters', []);

        if ($parentFilters) {
            $filters = array_merge($parentFilters, $filters);
        }

        $attributes = $parentModelMetadata->getAttributes();

        if (!$attributes) {
            $attributes = [];
        }

        return $parentModelMetadata->withAttributes(array_merge($attributes, ['filters' => $filters]));
    }

    /**
     * @throws ResourceClassNotFoundException
     */
    private function handleNotFound(?ResourceMetadata $parentPropertyMetadata, string $modelClass): ResourceMetadata
    {
        if (null !== $parentPropertyMetadata) {
            return $parentPropertyMetadata;
        }

        throw new ResourceClassNotFoundException(sprintf('Model "%s" not found.', $modelClass));
    }
}
