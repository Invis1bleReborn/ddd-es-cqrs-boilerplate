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

namespace Common\Shared\Domain;

use Broadway\EventSourcing\AggregateFactory\AggregateFactory;
use Broadway\EventSourcing\AggregateFactory\ReflectionAggregateFactory;
use Broadway\EventSourcing\Testing\AggregateRootScenarioTestCase;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Common\Shared\Domain\ValueObject\DateTime;
use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;
use Common\Shared\Infrastructure\Uuid\UuidGenerator;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class UuidGeneratorAwareAggregateRootScenarioTestCase
 *
 * @package Common\Shared\Domain
 */
abstract class UuidGeneratorAwareAggregateRootScenarioTestCase extends AggregateRootScenarioTestCase
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

    protected function getAggregateRootFactory(): AggregateFactory
    {
        return new ReflectionAggregateFactory();
    }

}
