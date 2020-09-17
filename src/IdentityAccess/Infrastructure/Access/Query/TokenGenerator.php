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

use Common\Shared\Domain\ValueObject\DateTime;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use IdentityAccess\Application\Query\Access\AccessTokenGeneratorInterface;
use IdentityAccess\Application\Query\Access\TokenGeneratorInterface;
use IdentityAccess\Application\Query\Access\TokenInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;

/**
 * Class TokenGenerator.
 */
final class TokenGenerator implements TokenGeneratorInterface
{
    private AccessTokenGeneratorInterface $accessTokenGenerator;

    private RefreshTokenManagerInterface $refreshTokenManager;

    private int $refreshTokenTtl;

    public function __construct(
        AccessTokenGeneratorInterface $accessTokenGenerator,
        RefreshTokenManagerInterface $refreshTokenManager,
        int $refreshTokenTtl
    ) {
        $this->accessTokenGenerator = $accessTokenGenerator;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->refreshTokenTtl = $refreshTokenTtl;
    }

    public function __invoke(UserInterface $user): TokenInterface
    {
        $refreshTokenDateExpired = new \DateTime();
        $refreshTokenDateExpired->modify('+' . $this->refreshTokenTtl . ' seconds');

        $refreshToken = $this->refreshTokenManager->create()
            ->setRefreshToken()
            ->setValid($refreshTokenDateExpired)
            ->getRefreshToken();

        return new Token(
            ($this->accessTokenGenerator)($user)->toString(),
            $refreshToken,
            DateTime::fromNative($refreshTokenDateExpired)
        );
    }
}
