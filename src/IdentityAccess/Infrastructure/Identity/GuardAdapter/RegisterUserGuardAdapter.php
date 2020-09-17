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

namespace IdentityAccess\Infrastructure\Identity\GuardAdapter;

use IdentityAccess\Infrastructure\Access\Security\AbstractGuardAdapter;
use IdentityAccess\Ui\Access\AccessAttribute;
use IdentityAccess\Ui\Identity\RegisterUser\RegisterUserGuard;

/**
 * Class RegisterUserGuardAdapter.
 */
class RegisterUserGuardAdapter extends AbstractGuardAdapter
{
    public function __construct(RegisterUserGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return 'user' === $subject
            && $attribute instanceof AccessAttribute
            && 'register' === $attribute->attribute
            && null === $attribute->field
        ;
    }
}
