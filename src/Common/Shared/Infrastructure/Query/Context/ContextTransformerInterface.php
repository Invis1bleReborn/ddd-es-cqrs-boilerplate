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

use Common\Shared\Application\Query\ListQueryInterface;

/**
 * Interface ContextTransformerInterface.
 */
interface ContextTransformerInterface
{
    /**
     * @throws \InvalidArgumentException
     */
    public function transform(array $context): ListQueryInterface;

    public function reverseTransform(ListQueryInterface $query): array;
}
