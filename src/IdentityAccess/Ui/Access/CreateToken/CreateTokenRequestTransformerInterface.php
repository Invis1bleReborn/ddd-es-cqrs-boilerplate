<?php

declare(strict_types=1);


namespace IdentityAccess\Ui\Access\CreateToken;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Access\CreateToken\CreateTokenCommand;

/**
 * Class CreateTokenRequestTransformer
 *
 * @package IdentityAccess\Ui\Access\CreateToken
 */
interface CreateTokenRequestTransformerInterface
{
    /**
     * @param CreateTokenRequest $request
     *
     * @return CreateTokenCommand
     * @throws ValidationException
     * @throws \IdentityAccess\Ui\Access\AccountStatusExceptionInterface
     * @throws AssertionFailedException
     */
    public function __invoke(CreateTokenRequest $request): CreateTokenCommand;

}