<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\RegisterUser;

/**
 * Class RegisterUserRequestTransformerDecorator
 *
 * @package IdentityAccess\Ui\Identity\RegisterUser
 */
abstract class RegisterUserRequestTransformerDecorator implements RegisterUserRequestTransformerInterface
{
    protected RegisterUserRequestTransformerInterface $transformer;

    public function __construct(RegisterUserRequestTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

}
