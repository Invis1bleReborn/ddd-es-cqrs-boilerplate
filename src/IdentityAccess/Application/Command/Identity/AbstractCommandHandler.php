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

use Common\Shared\Application\Bus\Command\CommandHandlerInterface;
use IdentityAccess\Domain\Identity\Repository\UserRepositoryInterface;
use IdentityAccess\Domain\Identity\User;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class AbstractCommandHandler.
 */
abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function getUser(UserId $userId): User
    {
        return $this->userRepository->get($userId);
    }

    protected function storeUser(User $user): void
    {
        $this->userRepository->store($user);
    }
}
