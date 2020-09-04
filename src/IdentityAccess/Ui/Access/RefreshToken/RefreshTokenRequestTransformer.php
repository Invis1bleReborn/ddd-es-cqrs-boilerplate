<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access\RefreshToken;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Common\Shared\Ui\ValidatorAwareRequestTransformer;
use IdentityAccess\Application\Command\Access\RefreshToken\RefreshTokenCommand;
use IdentityAccess\Application\Query\Access\RefreshTokenProviderInterface;
use IdentityAccess\Application\Query\Identity\UserProviderInterface;
use IdentityAccess\Ui\Access\RefreshTokenCheckerInterface;
use IdentityAccess\Ui\Access\UserCheckerInterface;

/**
 * Class RefreshTokenRequestTransformer
 *
 * @package IdentityAccess\Ui\Access\RefreshToken
 */
class RefreshTokenRequestTransformer extends ValidatorAwareRequestTransformer implements
    RefreshTokenRequestTransformerInterface
{
    private RefreshTokenProviderInterface $refreshTokenProvider;

    private RefreshTokenCheckerInterface $refreshTokenChecker;

    private UserProviderInterface $userProvider;

    private UserCheckerInterface $userChecker;

    public function __construct(
        ValidatorInterface $validator,
        RefreshTokenProviderInterface $refreshTokenProvider,
        RefreshTokenCheckerInterface $refreshTokenChecker,
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker
    )
    {
        parent::__construct($validator);

        $this->refreshTokenProvider = $refreshTokenProvider;
        $this->refreshTokenChecker = $refreshTokenChecker;
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(RefreshTokenRequest $request): RefreshTokenCommand
    {
        $this->validate($request);

        $refreshToken = $this->refreshTokenProvider->loadRefreshTokenByValue($request->refreshToken);

        ($this->refreshTokenChecker)($refreshToken);

        $user = $this->userProvider->loadUserByUsername($refreshToken->getUsername());

        ($this->userChecker)($user);

        return new RefreshTokenCommand(
            $user,
            $request->refreshToken
        );
    }

}
