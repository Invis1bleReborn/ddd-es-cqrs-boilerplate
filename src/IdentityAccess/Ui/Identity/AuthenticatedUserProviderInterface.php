<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity;

use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Interface AuthenticatedUserProviderInterface
 *
 * @package IIdentityAccess\Ui\Access
 */
interface AuthenticatedUserProviderInterface
{
    public function getUser(): ?UserInterface;

}
