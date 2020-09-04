<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Security;

use IdentityAccess\Ui\Access\AccessAttribute;
use IdentityAccess\Ui\Access\AccessCheckerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class AccessCheckerAdapter
 *
 * @package IdentityAccess\Infrastructure\Access\Security
 */
class AccessCheckerAdapter implements AccessCheckerInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted(
        string $attribute,
        $subject,
        string $field = null
    ): bool
    {
        return $this->authorizationChecker->isGranted(new AccessAttribute($attribute, $field), $subject);
    }

}
