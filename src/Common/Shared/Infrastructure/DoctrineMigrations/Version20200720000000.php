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

namespace DoctrineMigrations;

use Common\Shared\Infrastructure\Migrations\EventStoreAwareMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20200720000000
 */
class Version20200720000000 extends EventStoreAwareMigration
{
    public function up(Schema $schema): void
    {
        $this->eventStore->configureSchema($schema);

        $this->entityManager->flush();
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('events');

        $this->entityManager->flush();
    }
}
