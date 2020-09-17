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

namespace Common\Shared\Infrastructure\Migrations;

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory as BaseMigrationFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MigrationFactory
 *
 */
class MigrationFactory implements BaseMigrationFactory
{
    private BaseMigrationFactory $migrationFactory;

    private DBALEventStore $eventStore;

    private EntityManagerInterface $entityManager;

    public function __construct(
        BaseMigrationFactory $migrationFactory,
        DBALEventStore $eventStore,
        EntityManagerInterface $entityManager
    )
    {
        $this->migrationFactory = $migrationFactory;
        $this->eventStore = $eventStore;
        $this->entityManager = $entityManager;
    }

    public function createVersion(string $migrationClassName): AbstractMigration
    {
        $instance = $this->migrationFactory->createVersion($migrationClassName);

        if ($instance instanceof EventStoreAwareMigration) {
            $instance
                ->setEventStore($this->eventStore)
                ->setEntityManager($this->entityManager)
            ;
        }

        return $instance;
    }
}
