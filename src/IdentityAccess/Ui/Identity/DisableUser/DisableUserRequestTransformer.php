<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\DisableUser;

use IdentityAccess\Application\Command\Identity\DisableUser\DisableUserCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;
use IdentityAccess\Ui\Identity\ValidatorAwareRequestTransformer;

/**
 * Class DisableUserRequestTransformer
 *
 * @package IdentityAccess\Ui\Identity
 */
class DisableUserRequestTransformer extends ValidatorAwareRequestTransformer implements
    DisableUserRequestTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ChangeUserStatusRequest $request, UserInterface $user): DisableUserCommand
    {
        $this->validate($request);

        return new DisableUserCommand($user->getId(), $this->getAuthenticatedUserId());
    }

}
