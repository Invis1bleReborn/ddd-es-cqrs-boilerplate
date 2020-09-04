<?php

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
