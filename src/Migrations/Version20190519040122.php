<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190519040122 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->connection->exec("UPDATE federal_district SET code='centralniy' WHERE title='Центральный федеральный округ'");
        $this->connection->exec("UPDATE federal_district SET code='severozapadniy' WHERE title='Северо-Западный федеральный округ'");
        $this->connection->exec("UPDATE federal_district SET code='uzhniy' WHERE title='Южный федеральный округ'");
        $this->connection->exec("UPDATE federal_district SET code='severokavkaskiy' WHERE title='Северо–Кавказский федеральный округ'");
        $this->connection->exec("UPDATE federal_district SET code='prevolzhskiy' WHERE title='Приволжский федеральный округ'");
        $this->connection->exec("UPDATE federal_district SET code='uralskiy' WHERE title='Уральский федеральный округ'");
        $this->connection->exec("UPDATE federal_district SET code='sibirskiy' WHERE title='Сибирский федеральный округ'");
        $this->connection->exec("UPDATE federal_district SET code='dalnevostochniy' WHERE title='Дальневосточный федеральный округ'");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}