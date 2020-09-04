<?php

declare(strict_types=1);

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
