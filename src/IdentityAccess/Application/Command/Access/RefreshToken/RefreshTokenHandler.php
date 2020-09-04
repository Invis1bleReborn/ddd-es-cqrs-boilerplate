<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Command\Access\RefreshToken;

use Common\Shared\Application\Bus\Command\CommandHandlerInterface;
use Common\Shared\Application\Bus\Event\EventBusInterface;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Event\Access\TokenRefreshed;
use IdentityAccess\Application\Query\Access\AccessTokenGeneratorInterface;
use IdentityAccess\Application\Query\Access\TokenInterface;
use IdentityAccess\Infrastructure\Access\Query\Token;

/**
 * Class RefreshTokenHandler
 *
 * @package IdentityAccess\Application\Command\Access\RefreshToken
 */
final class RefreshTokenHandler implements CommandHandlerInterface
{
    private AccessTokenGeneratorInterface $accessTokenGenerator;

    private EventBusInterface $eventBus;

    private int $refreshTokenTtl;

    public function __construct(
        AccessTokenGeneratorInterface $accessTokenGenerator,
        EventBusInterface $eventBus,
        int $refreshTokenTtl
    )
    {
        $this->accessTokenGenerator = $accessTokenGenerator;
        $this->refreshTokenTtl = $refreshTokenTtl;
        $this->eventBus = $eventBus;
    }

    public function __invoke(RefreshTokenCommand $command): TokenInterface
    {
        $refreshTokenDateExpired = DateTime::fromNative(
            DateTime::now()
                ->toNative()
                ->modify('+' . $this->refreshTokenTtl . ' seconds')
        );

        $this->eventBus->handle(new TokenRefreshed(
            $command->refreshToken,
            $refreshTokenDateExpired
        ));

        return new Token(
            ($this->accessTokenGenerator)($command->user)->toString(),
            $command->refreshToken->toString(),
            $refreshTokenDateExpired
        );
    }

}
