<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Security;

use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessAttribute;
use IdentityAccess\Ui\Access\GuardInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class AbstractGuardAdapter
 *
 * @package IdentityAccess\Infrastructure\Access\Security
 */
abstract class AbstractGuardAdapter extends Voter
{
    protected GuardInterface $guard;

    /**
     * {@inheritdoc}
     *
     * @param AccessAttribute $attribute
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (is_object($subject)) {
            return $this->guard->isGranted($user, $subject);
        }

        return $this->guard->isGranted($user);
    }

}
