<?php

declare(strict_types=1);

namespace Common\Shared\Infrastructure\Migrations;

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EventStoreAwareMigration
 *
 * @package Common\Shared\Infrastructure\Migrations
 */
abstract class EventStoreAwareMigration extends AbstractMigration
{
    protected DBALEventStore $eventStore;

    protected EntityManagerInterface $entityManager;

    public function setEventStore(DBALEventStore $eventStore)
    {
        $this->eventStore = $eventStore;

        return $this;
    }

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

}
