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

use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Common\Shared\Application\Bus\Command\CommandHandlerInterface;
use Common\Shared\Application\Bus\Event\EventBusInterface;
use Common\Shared\CommandHandling\EventPublishingCommandHandlerScenarioTestCase;
use Common\Shared\Domain\ValueObject\DateTime;
use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;
use Common\Shared\Infrastructure\Uuid\UuidGenerator;
use IdentityAccess\Application\Event\Access\TokenRefreshed;
use IdentityAccess\Application\Query\Access\AccessTokenGeneratorInterface;
use IdentityAccess\Domain\Access\ValueObject\AccessToken;
use IdentityAccess\Domain\Access\ValueObject\RefreshToken;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Access\Query\Token;
use IdentityAccess\Infrastructure\Identity\Query\User;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class RefreshTokenHandlerTest.
 */
class RefreshTokenHandlerTest extends EventPublishingCommandHandlerScenarioTestCase
{
    private ?AccessToken $accessTokenMock;

    private UuidGeneratorInterface $uuidGenerator;

    private int $refreshTokenTtl = 42;

    protected function setUp(): void
    {
        $this->accessTokenMock = AccessToken::fromString('access_token');

        parent::setUp();

        $this->uuidGenerator = new UuidGenerator(new Version4Generator());
    }

    /**
     * @test
     */
    public function itRefreshesToken(): void
    {
        $refreshToken = 'refresh_token';
        $now = DateTime::now();

        $refreshTokenDateExpired = DateTime::fromNative(
            $now->toNative()
                ->modify('+' . $this->refreshTokenTtl . 'seconds')
        );

        ClockMock::withClockMock($now->toSeconds());

        $this->scenario
            ->when(new RefreshTokenCommand(
                new User(
                    UserId::fromString(($this->uuidGenerator)()),
                    Email::fromString('alice@acme.com'),
                    HashedPassword::fromString('some hash'),
                    Roles::fromArray([]),
                    true,
                    null,
                    DateTime::now()
                ),
                $refreshToken
            ))
            ->then(
                [
                    new TokenRefreshed(
                        RefreshToken::fromString($refreshToken),
                        $refreshTokenDateExpired
                    ),
                ],
                new Token(
                    $this->accessTokenMock->toString(),
                    $refreshToken,
                    $refreshTokenDateExpired
                )
            );
    }

    protected function createCommandHandler(EventBusInterface $eventBus): CommandHandlerInterface
    {
        $accessTokenGeneratorStub = $this->createStub(AccessTokenGeneratorInterface::class);

        $accessTokenGeneratorStub->method('__invoke')
            ->willReturn($this->accessTokenMock);

        return new RefreshTokenHandler(
            $accessTokenGeneratorStub,
            $eventBus,
            42
        );
    }
}
