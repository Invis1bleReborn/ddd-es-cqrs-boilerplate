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

use IdentityAccess\Application\Command\Identity\AbstractCommandHandler;
use IdentityAccess\Domain\Identity\PasswordEncoderInterface;
use IdentityAccess\Domain\Identity\Repository\UserRepositoryInterface;

/**
 * Class ChangePasswordHandler.
 */
final class ChangePasswordHandler extends AbstractCommandHandler
{
    private PasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct($userRepository);

        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(ChangePasswordCommand $command)
    {
        $user = $this->getUser($command->userId);

        $user->changePassword(
            $this->passwordEncoder->encode($command->plainPassword),
            $command->changedById
        );

        $this->storeUser($user);
    }
}
