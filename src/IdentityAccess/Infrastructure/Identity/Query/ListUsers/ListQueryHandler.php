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

namespace IdentityAccess\Infrastructure\Identity\Query\ListUsers;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use Common\Shared\Infrastructure\Query\AbstractQueryHandler;
use IdentityAccess\Infrastructure\Identity\Query\ListUsers\Context\UserListContextTransformer;
use IdentityAccess\Infrastructure\Identity\Query\UserModelClassSupportedTrait;

/**
 * Class ListQueryHandler.
 */
class ListQueryHandler extends AbstractQueryHandler
{
    use UserModelClassSupportedTrait;

    public function __construct(
        UserListContextTransformer $contextTransformer,
        ContextAwareCollectionDataProviderInterface $collectionProvider
    ) {
        parent::__construct($contextTransformer, $collectionProvider);
    }
}
