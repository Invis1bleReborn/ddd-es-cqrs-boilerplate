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

namespace IdentityAccess\Infrastructure\Identity\Query\ListUsers\Context;

use Common\Shared\Infrastructure\Query\Context\AbstractContextTransformer;
use IdentityAccess\Application\Query\Identity\ListUsers\ListQuery;
use IdentityAccess\Infrastructure\Identity\Query\User;

/**
 * Class UserListContextTransformer.
 */
class UserListContextTransformer extends AbstractContextTransformer
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getQueryClass(): string
    {
        return ListQuery::class;
    }
}
