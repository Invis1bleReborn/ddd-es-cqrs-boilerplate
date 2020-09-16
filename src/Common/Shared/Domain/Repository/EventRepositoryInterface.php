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

namespace Common\Shared\Domain\Repository;

/**
 * Interface EventRepositoryInterface
 *
 * @package Common\Shared\Domain\Repository
 */
interface EventRepositoryInterface
{
    public function page(int $page = 1, int $limit = 50): array;

}
