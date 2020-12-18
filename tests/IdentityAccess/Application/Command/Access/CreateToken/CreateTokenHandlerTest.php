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

namespace IdentityAccess\Application\Command\Access\CreateToken;

use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Common\Shared\Application\Command\CommandHandlerInterface;
use Common\Shared\Application\Event\EventBusInterface;
use Common\Shared\CommandHandling\EventPublishingCommandHandlerScenarioTestCase;
use Common\Shared\Domain\ValueObject\DateTime;
use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;
use Common\Shared\Infrastructure\Uuid\UuidGenerator;
use IdentityAccess\Application\Event\Access\TokenCreated;
use IdentityAccess\Application\Query\Access\TokenGeneratorInterface;
use IdentityAccess\Application\Query\Access\TokenInterface;
use IdentityAccess\Domain\Access\ValueObject\RefreshToken;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\PasswordCheckerInterface;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Domain\Identity\ValueObject\Username;
use IdentityAccess\Infrastructure\Access\Query\Token;
use IdentityAccess\Infrastructure\Identity\Query\User;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;

/**
 * Class CreateTokenHandlerTest.
 */
class CreateTokenHandlerTest extends EventPublishingCommandHandlerScenarioTestCase
{
    /**
     * @var PasswordCheckerInterface|InvocationMocker|null
     */
    private ?PasswordCheckerInterface $passwordCheckerStub;

    private ?TokenInterface $tokenMock;

    private UuidGeneratorInterface $uuidGenerator;

    protected function setUp(): void
    {
        $this->tokenMock = new Token('access_token', 'refresh_token', DateTime::now());

        parent::setUp();

        $this->uuidGenerator = new UuidGenerator(new Version4Generator());
    }

    /**
     * @test
     */
    public function itCreatesTokenWhenPasswordsMatches(): void
    {
        $username = 'alice@acme.com';

        $this->scenario
            ->when(new CreateTokenCommand(
                new User(
                    UserId::fromString($this->generateUuid()),
                    Email::fromString($username),
                    HashedPassword::fromString('some hash'),
                    Roles::fromArray([]),
                    true,
                    null,
                    DateTime::now()
                ),
                'some password'
            ))
            ->then(
                [
                    new TokenCreated(
                        RefreshToken::fromString($this->tokenMock->getRefreshToken()),
                        Username::fromString($username),
                        DateTime::fromNative($this->tokenMock->getRefreshTokenDateExpired())
                    ),
                ],
                $this->tokenMock
            );
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenPasswordIsNotValid(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('#(^|.*\s*)password invalid(?:\s*.*|$)#sui');

        $this->passwordCheckerStub->method('check')
            ->willThrowException(new \RuntimeException('Password invalid.'));

        $this->scenario
            ->when(new CreateTokenCommand(
                new User(
                    UserId::fromString($this->generateUuid()),
                    Email::fromString('alice@acme.com'),
                    HashedPassword::fromString('some hash'),
                    Roles::fromArray([]),
                    true,
                    null,
                    DateTime::now()
                ),
                'some password'
            ));
    }

    protected function generateUuid(): string
    {
        return ($this->uuidGenerator)();
    }

    protected function createCommandHandler(EventBusInterface $eventBus): CommandHandlerInterface
    {
        $this->passwordCheckerStub = $this->createStub(PasswordCheckerInterface::class);

        $tokenGeneratorStub = $this->createStub(TokenGeneratorInterface::class);

        $tokenGeneratorStub->method('__invoke')
            ->willReturn($this->tokenMock);

        return new CreateTokenHandler(
            $this->passwordCheckerStub,
            $tokenGeneratorStub,
            $eventBus
        );
    }
}
