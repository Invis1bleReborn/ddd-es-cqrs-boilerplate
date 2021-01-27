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

use Common\Shared\Application\Query\Filter\FilterInterface;
use Common\Shared\Application\Query\Filter\MatchingStrategy;
use Common\Shared\Application\Query\Filter\Type;

/**
 * Class AbstractFilterDescriptorFactory.
 */
abstract class AbstractFilterDescriptorFactory implements CollectionMutatorDescriptorFactoryInterface
{
    use FilterIdGeneratorTrait;

    public function supports(string $fqcn): bool
    {
        if (!is_subclass_of($fqcn, FilterInterface::class)) {
            return false;
        }

        $type = call_user_func([$fqcn, 'getType']);
        /* @var $type Type */

        return $this->getSupportedType() === $type->getValue();
    }

    public function create(\ReflectionClass $filterClass): array
    {
        $filterClassName = $filterClass->getName();
        $filterClassName_ = $this->getFilterClass();
        $matchingStrategy = call_user_func([$filterClassName, 'getMatchingStrategy']);
        /* @var $matchingStrategy ?MatchingStrategy */

        $modelClassName = call_user_func([$filterClassName, 'getModelClass']);

        return [
            'id' => $this->generateFilterId($modelClassName, $filterClassName_),
            'class' => $filterClassName_,
            'model_class' => $modelClassName,
            'arguments' => [
                'properties' => [
                    call_user_func([$filterClassName, 'getPropertyName']) => null === $matchingStrategy ? null : strtolower($matchingStrategy->getValue()),
                ],
            ],
        ];
    }

    abstract protected function getSupportedType(): string;

    abstract protected function getFilterClass(): string;
}
