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

namespace IdentityAccess\Infrastructure\Access\Security;

use IdentityAccess\Ui\Access\AuthenticationExceptionInterface;

/**
 * Class AuthenticationException
 *
 * @package IdentityAccess\Infrastructure\Access\Security
 */
class AuthenticationException extends \Symfony\Component\Security\Core\Exception\AuthenticationException implements
    AuthenticationExceptionInterface
{


}
