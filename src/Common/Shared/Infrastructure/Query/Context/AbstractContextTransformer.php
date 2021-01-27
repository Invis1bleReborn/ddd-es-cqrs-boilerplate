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

namespace Common\Shared\Infrastructure\Query\Context;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Common\Shared\Application\Query\Filter\FilterInterface;
use Common\Shared\Application\Query\Filter\Filters;
use Common\Shared\Application\Query\ListQueryInterface;
use Common\Shared\Application\Query\Sorting\SortingInterface;
use Common\Shared\Application\Query\Sorting\Sortings;

/**
 * Class AbstractContextTransformer.
 */
abstract class AbstractContextTransformer implements ContextTransformerInterface
{
    /**
     * @var iterable<FilterFactoryInterface>
     */
    protected iterable $filterFactories;

    /**
     * @var iterable<SortingFactoryInterface>
     */
    protected iterable $sortingFactories;

    protected string $orderParameterName;

    protected bool $paginationEnabled;

    protected string $pageParameterName;

    protected bool $clientPageSize;

    protected string $pageSizeParameterName;

    protected int $defaultPageSize;

    /**
     * @param iterable<FilterFactoryInterface>  $filterFactories
     * @param iterable<SortingFactoryInterface> $sortingFactories
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        iterable $filterFactories,
        iterable $sortingFactories,
        string $orderParameterName,
        bool $paginationEnabled,
        bool $clientPaginationEnabled,
        string $pageParameterName,
        bool $clientPageSize,
        string $pageSizeParameterName,
        int $defaultPageSize
    ) {
        foreach ($filterFactories as $factory) {
            Assertion::isInstanceOf($factory, FilterFactoryInterface::class);
        }

        foreach ($sortingFactories as $factory) {
            Assertion::isInstanceOf($factory, SortingFactoryInterface::class);
        }

        $this->filterFactories = $filterFactories;
        $this->sortingFactories = $sortingFactories;
        $this->orderParameterName = $orderParameterName;
        $this->paginationEnabled = $paginationEnabled || $clientPaginationEnabled;
        $this->pageParameterName = $pageParameterName;
        $this->clientPageSize = $clientPageSize;
        $this->pageSizeParameterName = $pageSizeParameterName;
        $this->defaultPageSize = $defaultPageSize;
    }

    public function transform(array $context): ListQueryInterface
    {
        $queryClass = $this->getQueryClass();

        if (!is_subclass_of($queryClass, ListQueryInterface::class)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected class that implements interface "%s".',
                ListQueryInterface::class
            ));
        }

        if ($this->paginationEnabled) {
            if ($this->clientPageSize && isset($context['filters'][$this->pageSizeParameterName])) {
                $limit = $context['filters'][$this->pageSizeParameterName];
            } else {
                $limit = $this->defaultPageSize;
            }

            $offset = (($context['filters'][$this->pageParameterName] ?? 1) - 1) * $limit;
        } else {
            $limit = null;
            $offset = null;
        }

        return call_user_func(
            [$queryClass, 'create'],
            $this->createFilters($context['filters'] ?? []),
            $this->createSortings($context['filters'][$this->orderParameterName] ?? []),
            $limit,
            $offset
        );
    }

    public function reverseTransform(ListQueryInterface $query): array
    {
        $context = [
            'groups' => [],
            'operation_type' => 'collection',
            'collection_operation_name' => 'get',
            'resource_class' => $this->getModelClass(),
            'input' => null,
            'output' => null,
        ];

        $filters = [];

        if ($this->paginationEnabled) {
            $offset = $query->getOffset();
            $pageSize = $context[$this->pageSizeParameterName] ?? $this->defaultPageSize;

            if (null !== $offset && 0 !== $offset) {
                $page = floor($offset / $pageSize);

                if ($page > 1) {
                    $filters[$this->pageParameterName] = (string)$page;
                }
            }

            if ($this->clientPageSize) {
                $limit = $query->getLimit();

                if (null !== $limit && $limit !== $this->defaultPageSize) {
                    $filters[$this->pageSizeParameterName] = (string)$limit;
                }
            }
        }

        $filters = array_merge($filters, $this->createContextFragmentFromFilters($query->getFilters()));
        $sortingsContextFragment = $this->createContextFragmentFromSortings($query->getSortings());

        if (!empty($sortingsContextFragment)) {
            $filters[$this->orderParameterName] = $sortingsContextFragment;
        }

        if (!empty($filters)) {
            $context['filters'] = $filters;
        }

        return $context;
    }

    protected function createFilters(array $context): ?Filters
    {
        $modelClass = $this->getModelClass();

        $filters = [];

        foreach ($this->filterFactories as $filterFactory) {
            /* @var $filterFactory FilterFactoryInterface */
            if (!$filterFactory->supportsContext($modelClass, $context)) {
                continue;
            }

            $filters[] = $filterFactory->createFilter($context);
        }

        if (empty($filters)) {
            return null;
        }

        return new Filters($filters);
    }

    protected function createContextFragmentFromFilters(?Filters $filters): array
    {
        $fragment = [];

        if (null === $filters) {
            return $fragment;
        }

        foreach ($filters as $filter) {
            /* @var $filter FilterInterface */

            foreach ($this->filterFactories as $filterFactory) {
                /* @var $filterFactory FilterFactoryInterface */
                if (!$filterFactory->supportsFilter($filter)) {
                    continue;
                }

                $fragment = array_merge($fragment, $filterFactory->createContextFragment($filter));
            }
        }

        return $fragment;
    }

    protected function createSortings(array $context): ?Sortings
    {
        $modelClass = $this->getModelClass();

        $sortings = [];

        foreach ($this->sortingFactories as $sortingFactory) {
            /* @var $sortingFactory SortingFactoryInterface */
            if (!$sortingFactory->supportsContext($modelClass, $context)) {
                continue;
            }

            $sortings[] = $sortingFactory->createSorting($context);
        }

        if (empty($sortings)) {
            return null;
        }

        return new Sortings($sortings);
    }

    protected function createContextFragmentFromSortings(?Sortings $sortings): array
    {
        $fragment = [];

        if (null === $sortings) {
            return $fragment;
        }

        foreach ($sortings as $sorting) {
            /* @var $sorting SortingInterface */

            foreach ($this->sortingFactories as $sortingFactory) {
                /* @var $sortingFactory SortingFactoryInterface */
                if (!$sortingFactory->supportsSorting($sorting)) {
                    continue;
                }

                $fragment = array_merge($fragment, $sortingFactory->createContextFragment($sorting));
            }
        }

        return $fragment;
    }

    abstract protected function getModelClass(): string;

    abstract protected function getQueryClass(): string;
}
