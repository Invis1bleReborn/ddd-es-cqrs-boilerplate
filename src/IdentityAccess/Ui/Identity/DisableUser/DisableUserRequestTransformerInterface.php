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

namespace IdentityAccess\Ui\Identity\DisableUser;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\DisableUser\DisableUserCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;

/**
 * Class DisableUserRequestTransformer
 *
 */
interface DisableUserRequestTransformerInterface
{
    /**
     * @param ChangeUserStatusRequest $request
     * @param UserInterface           $user
     *
     * @return DisableUserCommand
     * @throws AccessDeniedException
     * @throws ValidationException
     * @throws AssertionFailedException
     */
    public function __invoke(ChangeUserStatusRequest $request, UserInterface $user): DisableUserCommand;
}