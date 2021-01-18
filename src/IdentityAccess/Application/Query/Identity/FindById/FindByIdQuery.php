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

use Assert\AssertionFailedException;
use Common\Shared\Application\Query\QueryInterface;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class FindByIdQuery.
 */
class FindByIdQuery implements QueryInterface
{
    public UserId $userId;

    /**
     * FindByIdQuery constructor.
     *
     * @throws AssertionFailedException
     */
    public function __construct(string $id)
    {
        $this->userId = UserId::fromString($id);
    }
}
