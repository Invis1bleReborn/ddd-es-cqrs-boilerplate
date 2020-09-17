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

namespace IdentityAccess\Application\Command\Access\RefreshToken;

use Common\Shared\Application\Bus\Command\CommandHandlerInterface;
use Common\Shared\Application\Bus\Event\EventBusInterface;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Event\Access\TokenRefreshed;
use IdentityAccess\Application\Query\Access\AccessTokenGeneratorInterface;
use IdentityAccess\Application\Query\Access\TokenInterface;
use IdentityAccess\Infrastructure\Access\Query\Token;

/**
 * Class RefreshTokenHandler.
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
    ) {
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
