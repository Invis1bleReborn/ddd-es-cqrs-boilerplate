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

/**
 * Class AbstractMutatorFactory.
 */
abstract class AbstractMutatorFactory
{
    public function supportsContext(string $modelClass, array $context): bool
    {
        return isset($context[$this->getSupportedContextKey()]) &&
            $this->getSupportedModelClass() === $modelClass;
    }

    public function createContextFragment($mutator): array
    {
        return [
            $this->getSupportedContextKey() => $this->createContextValue($mutator),
        ];
    }

    abstract protected function getSupportedContextKey(): string;

    abstract protected function getSupportedModelClass(): string;

    abstract protected function createContextValue($mutator);
}
