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

namespace IdentityAccess\Application\Command\Identity;

use Assert\AssertionFailedException;
use Common\Shared\Application\Command\CommandInterface;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class UserIdAwareCommand.
 */
abstract class UserIdAwareCommand implements CommandInterface
{
    public UserId $userId;

    /**
     * UserIdAwareCommand constructor.
     *
     * @throws AssertionFailedException
     */
    public function __construct(string $userId)
    {
        $this->userId = UserId::fromString($userId);
    }
}
