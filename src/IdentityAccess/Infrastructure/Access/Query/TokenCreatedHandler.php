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

namespace IdentityAccess\Infrastructure\Access\Query;

use Common\Shared\Application\Bus\Event\EventHandlerInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use IdentityAccess\Application\Event\Access\TokenCreated;
 
/**
 * Class TokenCreatedHandler
 *
 * @package IdentityAccess\Infrastructure\Access\Query
 */
class TokenCreatedHandler implements EventHandlerInterface
{
    private RefreshTokenManagerInterface $refreshTokenManager;

    public function __construct(RefreshTokenManagerInterface $refreshTokenManager)
    {
        $this->refreshTokenManager = $refreshTokenManager;
    }

    public function __invoke(TokenCreated $event): void
    {
        $refreshTokenDateExpired = $event->refreshTokenDateExpired()->toNative(\DateTime::class);
        /* @var $refreshTokenDateExpired \DateTime */

        $refreshToken = $this->refreshTokenManager->create()
            ->setUsername($event->username())
            ->setRefreshToken($event->refreshToken())
            ->setValid($refreshTokenDateExpired);

        $this->refreshTokenManager->save($refreshToken);
    }

}
