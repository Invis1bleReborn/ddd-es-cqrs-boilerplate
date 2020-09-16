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

use IdentityAccess\Application\Command\Identity\AbstractCommandHandler;
use IdentityAccess\Domain\Identity\PasswordEncoderInterface;
use IdentityAccess\Domain\Identity\Repository\UserRepositoryInterface;
use IdentityAccess\Domain\Identity\Specification\UniqueEmailSpecificationInterface;
use IdentityAccess\Domain\Identity\User;

/**
 * Class RegisterUserHandler
 *
 * @package IdentityAccess\Application\Command\Identity\RegisterUser
 */
final class RegisterUserHandler extends AbstractCommandHandler
{
    private PasswordEncoderInterface $passwordEncoder;

    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordEncoderInterface $passwordEncoder,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    )
    {
        parent::__construct($userRepository);

        $this->passwordEncoder = $passwordEncoder;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        $user = User::register(
            $command->userId,
            $command->email,
            $this->passwordEncoder->encode($command->plainPassword),
            $command->roles,
            $command->enabled,
            $command->registeredById,
            $this->uniqueEmailSpecification
        );

        $this->storeUser($user);
    }

}
