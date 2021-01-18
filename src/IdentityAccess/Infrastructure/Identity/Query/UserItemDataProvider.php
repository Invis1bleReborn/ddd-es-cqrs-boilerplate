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

namespace IdentityAccess\Infrastructure\Identity\Query;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Common\Shared\Application\Query\QueryBusInterface;
use IdentityAccess\Application\Query\Identity\FindById\FindByIdQuery;
use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Class UserItemDataProvider.
 */
class UserItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?UserInterface {
        return $this->queryBus->ask(new FindByIdQuery($id));
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return User::class === $resourceClass;
    }
}
