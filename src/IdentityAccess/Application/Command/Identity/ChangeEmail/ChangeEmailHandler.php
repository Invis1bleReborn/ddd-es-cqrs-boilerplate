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

use IdentityAccess\Application\Command\Identity\AbstractCommandHandler;
use IdentityAccess\Domain\Identity\Repository\UserRepositoryInterface;
use IdentityAccess\Domain\Identity\Specification\UniqueEmailSpecificationInterface;

/**
 * Class ChangeEmailHandler.
 */
final class ChangeEmailHandler extends AbstractCommandHandler
{
    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        parent::__construct($userRepository);

        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function __invoke(ChangeEmailCommand $command)
    {
        $user = $this->getUser($command->userId);

        $user->changeEmail(
            $command->email,
            $command->changedById,
            $this->uniqueEmailSpecification
        );

        $this->storeUser($user);
    }
}
