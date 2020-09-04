<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Identity\AuthenticatedUserProvider;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Identity\AuthenticatedUserProviderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class AuthenticatedUserProvider
 *
 * @package IdentityAccess\Infrastructure\Identity\AuthenticatedUserProvider
 */
final class AuthenticatedUserProvider implements AuthenticatedUserProviderInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser(): ?UserInterface
    {
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            return $user;
        }

        return null;
    }

}
