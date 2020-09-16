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
