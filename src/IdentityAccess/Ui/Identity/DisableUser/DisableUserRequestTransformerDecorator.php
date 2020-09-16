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
