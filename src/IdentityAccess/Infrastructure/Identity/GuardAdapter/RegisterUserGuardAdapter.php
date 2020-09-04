<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Identity\GuardAdapter;

use IdentityAccess\Infrastructure\Access\Security\AbstractGuardAdapter;
use IdentityAccess\Ui\Access\AccessAttribute;
use IdentityAccess\Ui\Identity\RegisterUser\RegisterUserGuard;

/**
 * Class RegisterUserGuardAdapter
 *
 * @package IdentityAccess\Infrastructure\Identity\GuardAdapter
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
