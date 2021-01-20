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

namespace IdentityAccess\Ui\Access;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class JwtNotFoundListener.
 */
class JwtNotFoundListener
{
    public function __invoke(JWTNotFoundEvent $event): void
    {
        if (!$event->getResponse() instanceof JWTAuthenticationFailureResponse) {
            return;
        }

        $previous = $event->getException()
            ->getPrevious();

        if (!$previous instanceof AuthenticationException) {
            return;
        }

        throw $previous;
    }
}
