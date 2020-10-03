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

namespace IdentityAccess\Application\Command\Access\ChangeRoles;

use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\UserIdAwareCommand;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class ChangeRolesCommand.
 */
class ChangeRolesCommand extends UserIdAwareCommand
{
    public UserId $changedById;

    public Roles $roles;

    /**
     * ChangeRolesCommand constructor.
     *
     * {@inheritdoc}
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        string $userId,
        array $roles,
        string $changedById
    ) {
        parent::__construct($userId);

        $this->roles = Roles::fromArray($roles);
        $this->changedById = UserId::fromString($changedById);
    }
}
