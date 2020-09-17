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
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EventStoreAwareMigration.
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
