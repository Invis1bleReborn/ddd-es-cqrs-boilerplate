<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Common\Shared\Infrastructure\Migrations\EventStoreAwareMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20200720000000
 *
 * @package DoctrineMigrations
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
