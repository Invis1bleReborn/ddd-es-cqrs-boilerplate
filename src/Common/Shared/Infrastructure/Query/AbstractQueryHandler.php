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

namespace Common\Shared\Infrastructure\Query;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use Common\Shared\Application\Query\AbstractListQuery;
use Common\Shared\Application\Query\QueryHandlerInterface;
use Common\Shared\Infrastructure\Query\Context\ContextTransformerInterface;

/**
 * Class AbstractQueryHandler.
 */
abstract class AbstractQueryHandler implements QueryHandlerInterface
{
    protected ContextTransformerInterface $contextTransformer;

    protected ContextAwareCollectionDataProviderInterface $collectionProvider;

    public function __construct(
        ContextTransformerInterface $contextTransformer,
        ContextAwareCollectionDataProviderInterface $collectionProvider
    ) {
        $this->contextTransformer = $contextTransformer;
        $this->collectionProvider = $collectionProvider;
    }

    public function __invoke(AbstractListQuery $query): iterable
    {
        return $this->collectionProvider->getCollection(
            $this->getSupportedModelClass(),
            null,
            $this->contextTransformer->reverseTransform($query)
        );
    }

    abstract protected function getSupportedModelClass(): string;
}
