<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Interface UserCheckerInterface
 *
 * @package IdentityAccess\Ui\Access
 */
interface UserCheckerInterface
{
    /**
     * @param UserInterface $user
     *
     * @throws AccountStatusExceptionInterface
     */
    public function __invoke(UserInterface $user): void;

}
