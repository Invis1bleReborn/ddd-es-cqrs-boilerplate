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

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Common\Shared\Application\Query\QueryBusInterface;
use IdentityAccess\Infrastructure\Identity\Query\ListUsers\Context\UserListContextTransformer;

/**
 * Class UserCollectionDataProvider.
 */
class UserCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private UserListContextTransformer $contextTransformer;

    private QueryBusInterface $queryBus;

    public function __construct(UserListContextTransformer $contextTransformer, QueryBusInterface $queryBus)
    {
        $this->contextTransformer = $contextTransformer;
        $this->queryBus = $queryBus;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return $this->queryBus->ask($this->contextTransformer->transform($context));
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return User::class === $resourceClass;
    }
}
