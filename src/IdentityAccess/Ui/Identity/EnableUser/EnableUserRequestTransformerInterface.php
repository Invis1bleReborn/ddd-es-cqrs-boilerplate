<?php

declare(strict_types=1);


namespace IdentityAccess\Ui\Identity\EnableUser;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\EnableUser\EnableUserCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;

/**
 * Class EnableUserRequestTransformer
 *
 * @package IdentityAccess\Ui\Identity
 */
interface EnableUserRequestTransformerInterface
{
    /**
     * @param ChangeUserStatusRequest $request
     * @param UserInterface           $user
     *
     * @return EnableUserCommand
     * @throws AccessDeniedException
     * @throws ValidationException
     * @throws AssertionFailedException
     */
    public function __invoke(ChangeUserStatusRequest $request, UserInterface $user): EnableUserCommand;

}