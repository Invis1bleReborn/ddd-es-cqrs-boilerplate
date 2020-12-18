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

namespace IdentityAccess\Application\Query\Identity\FindById;

use Common\Shared\Application\Query\QueryHandlerInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Class FindByIdHandler.
 */
class FindByIdHandler implements QueryHandlerInterface
{
    private FindUserByIdInterface $repository;

    public function __construct(FindUserByIdInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindByIdQuery $query): ?UserInterface
    {
        return $this->repository->findById($query->userId);
    }
}
