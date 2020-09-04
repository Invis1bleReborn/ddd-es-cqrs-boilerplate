<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Command\Identity;

use Common\Shared\Application\Bus\Command\CommandHandlerInterface;
use IdentityAccess\Domain\Identity\Repository\UserRepositoryInterface;
use IdentityAccess\Domain\Identity\User;
use IdentityAccess\Domain\Identity\ValueObject\UserId;

/**
 * Class AbstractCommandHandler
 *
 * @package IdentityAccess\Application\Command\Identity
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
