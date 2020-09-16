<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IdentityAccess\Application\Command\Identity;

use Assert\AssertionFailedException;
use Common\Shared\Application\Bus\Command\CommandInterface;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserIdAwareCommand
 *
 * @package IdentityAccess\Application\Command\Identity
 */
abstract class UserIdAwareCommand implements CommandInterface
{
    public UserId $userId;

    /**
     * UserIdAwareCommand constructor.
     *
     * @param string $userId
     *
     * @throws AssertionFailedException
     */
    public function __construct(string $userId)
    {
        $this->userId = UserId::fromString($userId);
    }

}
