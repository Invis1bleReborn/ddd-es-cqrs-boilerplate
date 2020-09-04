<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\DisableUser;

/**
 * Class DisableUserRequestTransformerDecorator
 *
 * @package IdentityAccess\Ui\Identity\DisableUser
 */
abstract class DisableUserRequestTransformerDecorator implements DisableUserRequestTransformerInterface
{
    protected DisableUserRequestTransformerInterface $transformer;

    public function __construct(DisableUserRequestTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

}
