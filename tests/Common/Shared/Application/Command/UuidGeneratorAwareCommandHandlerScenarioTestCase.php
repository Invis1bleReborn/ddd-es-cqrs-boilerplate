<?php

declare(strict_types=1);

namespace Common\Shared\Application\Command;

use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Common\Shared\Domain\ValueObject\DateTime;
use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;
use Common\Shared\Infrastructure\Uuid\UuidGenerator;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class UuidGeneratorAwareCommandHandlerScenarioTestCase
 *
 * @package Common\Shared\Application\Command
 */
abstract class UuidGeneratorAwareCommandHandlerScenarioTestCase extends CommandHandlerScenarioTestCase
{
    private UuidGeneratorInterface $uuidGenerator;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        ClockMock::register(DateTime::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->uuidGenerator = new UuidGenerator(new Version4Generator());
    }

    protected function generateUuid(): string
    {
        return ($this->uuidGenerator)();
    }

}
