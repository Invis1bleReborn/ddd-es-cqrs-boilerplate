<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\EnableUser;

/**
 * Class EnableUserRequestTransformerDecorator
 *
 * @package IdentityAccess\Ui\Identity\EnableUser
 */
abstract class EnableUserRequestTransformerDecorator implements EnableUserRequestTransformerInterface
{
    protected EnableUserRequestTransformerInterface $transformer;

    public function __construct(EnableUserRequestTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

}
