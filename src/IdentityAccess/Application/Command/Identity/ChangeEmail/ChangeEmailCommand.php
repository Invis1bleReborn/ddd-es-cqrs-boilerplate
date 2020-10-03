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

namespace IdentityAccess\Application\Command\Identity\ChangeEmail;

use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\UserIdAwareCommand;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class ChangeEmailCommand.
 */
class ChangeEmailCommand extends UserIdAwareCommand
{
    public UserId $changedById;

    public Email $email;

    /**
     * ChangeEmailCommand constructor.
     *
     * {@inheritdoc}
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        string $userId,
        string $email,
        string $changedById
    ) {
        parent::__construct($userId);

        $this->email = Email::fromString($email);
        $this->changedById = UserId::fromString($changedById);
    }
}
