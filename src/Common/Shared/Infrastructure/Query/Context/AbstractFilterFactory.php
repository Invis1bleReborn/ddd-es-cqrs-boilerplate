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

use Common\Shared\Application\Query\Filter\FilterInterface;

/**
 * Class AbstractFilterFactory.
 */
abstract class AbstractFilterFactory extends AbstractMutatorFactory implements FilterFactoryInterface
{
    public function supportsFilter(FilterInterface $filter): bool
    {
        $filterClass = $this->getSupportedFilterClass();

        return $filter instanceof $filterClass;
    }

    public function createFilter(array $context): FilterInterface
    {
        return $this->createFilterFromContextValue($context[$this->getSupportedContextKey()]);
    }

    abstract protected function getSupportedFilterClass(): string;

    abstract protected function createFilterFromContextValue($value): FilterInterface;
}
