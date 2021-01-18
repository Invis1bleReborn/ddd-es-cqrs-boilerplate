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

namespace Common\Shared\Infrastructure\DependencyInjection\CollectionMutator\DescriptorFactory;

use Common\Shared\Application\Query\Sorting\Direction;
use Common\Shared\Application\Query\Sorting\NullsComparisonStrategy;
use Common\Shared\Application\Query\Sorting\SortingInterface;

/**
 * Class AbstractSortingDescriptorFactory.
 */
abstract class AbstractSortingDescriptorFactory implements CollectionMutatorDescriptorFactoryInterface
{
    use FilterIdGeneratorTrait;

    public function supports(string $fqcn): bool
    {
        if (is_subclass_of($fqcn, SortingInterface::class)) {
            return true;
        }

        return false;
    }

    public function create(\ReflectionClass $sortingClass): array
    {
        $sortingClassName = $sortingClass->getName();
        $filterClassName = $this->getFilterClass();
        $nullsComparisonStrategy = call_user_func([$sortingClassName, 'getNullsComparisonStrategy']);
        /* @var $nullsComparisonStrategy ?NullsComparisonStrategy */

        if (null === $nullsComparisonStrategy) {
            $nullsComparisonStrategyValue = null;
        } else {
            $nullsComparisonStrategyValue = $nullsComparisonStrategy->getValue();
        }

        $filterArguments = [
            'nulls_comparison' => null === $nullsComparisonStrategyValue ?
                null :
                strtolower($nullsComparisonStrategyValue),
        ];

        $defaultDirection = call_user_func([$sortingClassName, 'getDefaultDirection']);
        /* @var $defaultDirection ?Direction */

        if (null !== $defaultDirection) {
            $filterArguments['default_direction'] = $defaultDirection->getValue();
        }

        $modelClassName = call_user_func([$sortingClassName, 'getModelClass']);

        return [
            'id' => $this->generateFilterId($modelClassName, $filterClassName),
            'class' => $filterClassName,
            'model_class' => $modelClassName,
            'arguments' => [
                'properties' => [
                    call_user_func([$sortingClassName, 'getPropertyName']) => $filterArguments,
                ],
            ],
        ];
    }

    abstract protected function getFilterClass(): string;
}
