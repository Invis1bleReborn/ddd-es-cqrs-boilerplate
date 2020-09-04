<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Identity\GuardAdapter;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Infrastructure\Access\Security\AbstractGuardAdapter;
use IdentityAccess\Ui\Access\AccessAttribute;
use IdentityAccess\Ui\Identity\EnableUser\EnableUserGuard;

/**
 * Class EnableUserGuardAdapter
 *
 * @package IdentityAccess\Infrastructure\Identity\GuardAdapter
 */
class EnableUserGuardAdapter extends AbstractGuardAdapter
{
    public function __construct(EnableUserGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof UserInterface
            && $attribute instanceof AccessAttribute
            && 'enable' === $attribute->attribute
            && null === $attribute->field
        ;
    }

}
