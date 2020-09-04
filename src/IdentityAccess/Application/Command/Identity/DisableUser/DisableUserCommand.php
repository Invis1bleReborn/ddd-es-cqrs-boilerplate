<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Command\Identity\DisableUser;

use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\ChangeUserStatusCommandInterface;
use IdentityAccess\Application\Command\Identity\UserIdAwareCommand;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class DisableUserCommand
 *
 * @package IdentityAccess\Application\Command\Identity\DisableUser
 */
class DisableUserCommand extends UserIdAwareCommand implements ChangeUserStatusCommandInterface
{
    public UserId $disabledById;

    /**
     * DisableUserCommand constructor.
     *
     * {@inheritdoc}
     *
     * @param string $disabledById
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        string $userId,
        string $disabledById
    )
    {
        parent::__construct($userId);

        $this->disabledById = UserId::fromString($disabledById);
    }

}
