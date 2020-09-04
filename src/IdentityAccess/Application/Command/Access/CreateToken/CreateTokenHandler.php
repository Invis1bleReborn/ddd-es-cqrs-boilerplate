<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Command\Access\CreateToken;

use Common\Shared\Application\Bus\Command\CommandHandlerInterface;
use Common\Shared\Application\Bus\Event\EventBusInterface;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Query\Access\TokenGeneratorInterface;
use IdentityAccess\Application\Query\Access\TokenInterface;
use IdentityAccess\Application\Event\Access\TokenCreated;
use IdentityAccess\Domain\Access\ValueObject\RefreshToken;
use IdentityAccess\Domain\Identity\PasswordCheckerInterface;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\Username;

/**
 * Class CreateTokenHandler
 *
 * @package IdentityAccess\Application\Command\Access\CreateToken
 */
final class CreateTokenHandler implements CommandHandlerInterface
{
    private PasswordCheckerInterface $passwordChecker;

    private TokenGeneratorInterface $tokenGenerator;

    private EventBusInterface $eventBus;

    public function __construct(
        PasswordCheckerInterface $passwordChecker,
        TokenGeneratorInterface $tokenGenerator,
        EventBusInterface $eventBus
    )
    {
        $this->passwordChecker = $passwordChecker;
        $this->tokenGenerator = $tokenGenerator;
        $this->eventBus = $eventBus;
    }

    public function __invoke(CreateTokenCommand $command): TokenInterface
    {
        $this->passwordChecker->check(
            HashedPassword::fromString($command->user->getHashedPassword()),
            $command->plainPassword,
            null
        );

        $token = ($this->tokenGenerator)($command->user);

        $this->eventBus->handle(new TokenCreated(
            RefreshToken::fromString($token->getRefreshToken()),
            Username::fromString($command->user->getEmail()),
            DateTime::fromNative($token->getRefreshTokenDateExpired())
        ));

        return $token;
    }

}
