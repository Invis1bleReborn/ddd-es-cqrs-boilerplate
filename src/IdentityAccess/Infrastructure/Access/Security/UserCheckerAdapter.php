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

namespace IdentityAccess\Infrastructure\Access\Security;

use IdentityAccess\Ui\Access\UserChecker;
use IdentityAccess\Ui\Access\AccountStatusExceptionInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserCheckerAdapter
 */
class UserCheckerAdapter implements UserCheckerInterface
{
    private \IdentityAccess\Ui\Access\UserCheckerInterface $userChecker;

    public function __construct(UserChecker $userChecker)
    {
        $this->userChecker = $userChecker;
    }

    /**
     * @throws AccountStatusExceptionInterface
     * @throws \UnexpectedValueException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof \IdentityAccess\Application\Query\Identity\UserInterface) {
            throw new \UnexpectedValueException(sprintf(
                'Expected %s, % given.',
                \IdentityAccess\Application\Query\Identity\UserInterface::class,
                get_class($user)
            ));
        }

        ($this->userChecker)($user);
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // no op
    }
}
