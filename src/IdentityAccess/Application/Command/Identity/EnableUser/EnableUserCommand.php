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

namespace IdentityAccess\Application\Command\Identity\EnableUser;

use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\ChangeUserStatusCommandInterface;
use IdentityAccess\Application\Command\Identity\UserIdAwareCommand;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class EnableUserCommand
 *
 * @package IdentityAccess\Application\Command\Identity\EnableUser
 */
class EnableUserCommand extends UserIdAwareCommand implements ChangeUserStatusCommandInterface
{
    public UserId $enabledById;

    /**
     * EnableUserCommand constructor.
     *
     * {@inheritdoc}
     *
     * @param string $enabledById
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        string $userId,
        string $enabledById
    )
    {
        parent::__construct($userId);

        $this->enabledById = UserId::fromString($enabledById);
    }
}
