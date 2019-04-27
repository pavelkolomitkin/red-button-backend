<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416161339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Importing federal districts';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->connection->exec("
        INSERT INTO federal_district (id, title) VALUES (1,N'Центральный федеральный округ');
        INSERT INTO federal_district (id, title) VALUES (2,N'Северо-Западный федеральный округ');
        INSERT INTO federal_district (id, title) VALUES (3,N'Южный федеральный округ');
        INSERT INTO federal_district (id, title) VALUES (4,N'Северо–Кавказский федеральный округ');
        INSERT INTO federal_district (id, title) VALUES (5,N'Приволжский федеральный округ');
        INSERT INTO federal_district (id, title) VALUES (6,N'Уральский федеральный округ');
        INSERT INTO federal_district (id, title) VALUES (7,N'Сибирский федеральный округ');
        INSERT INTO federal_district (id, title) VALUES (8,N'Дальневосточный федеральный округ');
        ");
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->connection->executeQuery("TRUNCATE federal_district CASCADE;");
    }
}
