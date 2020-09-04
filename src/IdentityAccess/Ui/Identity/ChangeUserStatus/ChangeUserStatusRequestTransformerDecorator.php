<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\ChangeUserStatus;

/**
 * Class ChangeUserStatusRequestTransformerDecorator
 *
 * @package IdentityAccess\Ui\Identity\ChangeUserStatus
 */
abstract class ChangeUserStatusRequestTransformerDecorator implements ChangeUserStatusRequestTransformerInterface
{
    protected ChangeUserStatusRequestTransformerInterface $transformer;

    public function __construct(ChangeUserStatusRequestTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

}
