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

namespace IdentityAccess\Application\Query\Identity\FindByUsername;

use Assert\AssertionFailedException;
use Common\Shared\Application\Query\QueryInterface;
use IdentityAccess\Domain\Identity\ValueObject\Username;

/**
 * Class FindByUsernameQuery.
 */
class FindByUsernameQuery implements QueryInterface
{
    public Username $username;

    /**
     * FindByUsernameQuery constructor.
     *
     * @throws AssertionFailedException
     */
    public function __construct(string $username)
    {
        $this->username = Username::fromString($username);
    }
}
