<?php

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
