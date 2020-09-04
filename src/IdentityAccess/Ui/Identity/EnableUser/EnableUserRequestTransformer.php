<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\EnableUser;

use IdentityAccess\Application\Command\Identity\EnableUser\EnableUserCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;
use IdentityAccess\Ui\Identity\ValidatorAwareRequestTransformer;

/**
 * Class EnableUserRequestTransformer
 *
 * @package IdentityAccess\Ui\Identity
 */
class EnableUserRequestTransformer extends ValidatorAwareRequestTransformer implements
    EnableUserRequestTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ChangeUserStatusRequest $request, UserInterface $user): EnableUserCommand
    {
        $this->validate($request);

        return new EnableUserCommand($user->getId(), $this->getAuthenticatedUserId());
    }

}
