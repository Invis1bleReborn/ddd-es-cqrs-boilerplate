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

namespace IdentityAccess\Application\Command\Identity\ChangePassword;

use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\UserIdAwareCommand;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class ChangePasswordCommand.
 */
class ChangePasswordCommand extends UserIdAwareCommand
{
    public UserId $changedById;

    public PlainPassword $plainPassword;

    /**
     * ChangePasswordCommand constructor.
     *
     * {@inheritdoc}
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        string $userId,
        string $plainPassword,
        string $changedById
    ) {
        parent::__construct($userId);

        $this->plainPassword = PlainPassword::fromString($plainPassword);
        $this->changedById = UserId::fromString($changedById);
    }
}
