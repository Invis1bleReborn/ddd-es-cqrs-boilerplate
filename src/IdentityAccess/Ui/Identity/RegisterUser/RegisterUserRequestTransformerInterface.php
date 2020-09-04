<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\RegisterUser;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Identity\RegisterUser\RegisterUserCommand;
use IdentityAccess\Ui\Access\AccessDeniedException;

/**
 * Interface RegisterUserRequestTransformerInterface
 *
 * @package IdentityAccess\Ui\Identity\RegisterUser
 */
interface RegisterUserRequestTransformerInterface
{
    /**
     * @param RegisterUserRequest $request
     *
     * @return RegisterUserCommand
     * @throws AccessDeniedException
     * @throws ValidationException
     * @throws AssertionFailedException
     */
    public function __invoke(RegisterUserRequest $request): RegisterUserCommand;

}
