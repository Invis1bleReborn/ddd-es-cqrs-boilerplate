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

namespace IdentityAccess\Application\Command\Identity\RegisterUser;

use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\UserIdAwareCommand;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\PlainPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class RegisterUserCommand.
 */
class RegisterUserCommand extends UserIdAwareCommand
{
    public Email $email;

    public PlainPassword $plainPassword;

    public bool $enabled;

    public Roles $roles;

    public ?UserId $registeredById;

    /**
     * RegisterUserCommand constructor.
     *
     * {@inheritdoc}
     *
     * @param string[] $roles
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        string $userId,
        string $email,
        string $plainPassword,
        bool $enabled,
        array $roles,
        ?string $registeredById
    ) {
        parent::__construct($userId);

        $this->email = Email::fromString($email);
        $this->plainPassword = PlainPassword::fromString($plainPassword);
        $this->enabled = $enabled;
        $this->roles = Roles::fromArray($roles);
        $this->registeredById = null === $registeredById ? null : UserId::fromString($registeredById);
    }
}
