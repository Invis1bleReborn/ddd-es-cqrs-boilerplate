<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Access\Query;

use Common\Shared\Application\Bus\Event\EventHandlerInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use IdentityAccess\Application\Event\Access\TokenRefreshed;

/**
 * Class TokenRefreshedHandler
 *
 * @package IdentityAccess\Infrastructure\Access\Query
 */
class TokenRefreshedHandler implements EventHandlerInterface
{
    private RefreshTokenManagerInterface $refreshTokenManager;

    public function __construct(RefreshTokenManagerInterface $refreshTokenManager)
    {
        $this->refreshTokenManager = $refreshTokenManager;
    }

    public function __invoke(TokenRefreshed $event): void
    {
        $refreshToken = $this->refreshTokenManager->get($event->refreshToken());

        $refreshTokenDateExpired = $event->refreshTokenDateExpired()->toNative(\DateTime::class);
        /* @var $refreshTokenDateExpired \DateTime */

        $refreshToken->setValid($refreshTokenDateExpired);

        $this->refreshTokenManager->save($refreshToken);
    }

}
