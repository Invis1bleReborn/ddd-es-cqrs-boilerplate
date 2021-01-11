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

use Common\Shared\Application\Query\Sorting\SortingInterface;

/**
 * Interface SortingFactoryInterface.
 */
interface SortingFactoryInterface
{
    public function supportsContext(string $modelClass, array $context): bool;

    public function supportsSorting(SortingInterface $sorting): bool;

    public function createSorting(array $context): SortingInterface;

    public function createContextFragment(SortingInterface $sorting): array;
}
