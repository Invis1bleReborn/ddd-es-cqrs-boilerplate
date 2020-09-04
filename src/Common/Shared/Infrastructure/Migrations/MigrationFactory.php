<?php

declare(strict_types=1);

namespace Common\Shared\Infrastructure\Migrations;

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory as BaseMigrationFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MigrationFactory
 *
 * @package Common\Shared\Infrastructure\Migrations
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
