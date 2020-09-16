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

namespace IdentityAccess\Ui\Identity\ChangeUserStatus;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\ChangeUserStatusCommandInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;

/**
 * Class ChangeUserStatusRequestTransformer
 *
 * @package IdentityAccess\Ui\Identity
 */
interface ChangeUserStatusRequestTransformerInterface
{
    /**
     * @param ChangeUserStatusRequest $request
     * @param UserInterface           $user
     *
     * @return ChangeUserStatusCommandInterface
     * @throws AccessDeniedException
     * @throws ValidationException
     * @throws AssertionFailedException
     */
    public function __invoke(ChangeUserStatusRequest $request, UserInterface $user): ChangeUserStatusCommandInterface;

}