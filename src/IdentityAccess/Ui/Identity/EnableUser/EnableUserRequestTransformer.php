<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
