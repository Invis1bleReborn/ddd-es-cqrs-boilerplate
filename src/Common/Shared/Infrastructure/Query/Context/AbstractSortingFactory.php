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

use Common\Shared\Application\Query\Sorting\Direction;
use Common\Shared\Application\Query\Sorting\SortingInterface;

/**
 * Class AbstractSortingFactory.
 */
abstract class AbstractSortingFactory extends AbstractMutatorFactory implements SortingFactoryInterface
{
    public function supportsSorting(SortingInterface $sorting): bool
    {
        $sortingClass = $this->getSupportedSortingClass();

        return $sorting instanceof $sortingClass;
    }

    public function createSorting(array $context): SortingInterface
    {
        return $this->createSortingFromDirection(new Direction(strtoupper($context[$this->getSupportedContextKey()])));
    }

    /**
     * @param SortingInterface $filter
     */
    protected function createContextValue($sorting): string
    {
        return $sorting->getDirection()
            ->getValue();
    }

    abstract protected function getSupportedSortingClass(): string;

    abstract protected function createSortingFromDirection(Direction $direction): SortingInterface;
}
