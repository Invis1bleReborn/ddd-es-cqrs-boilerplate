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
