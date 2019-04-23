<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423004925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->connection->executeQuery("ALTER SEQUENCE region_id_seq RESTART WITH 100;");

        $districtId = $this
            ->connection
            ->executeQuery("SELECT federal_district_id from region WHERE title = N'Московская область'")
            ->fetch(FetchMode::ASSOCIATIVE);

        $this
            ->connection
            ->executeQuery("INSERT INTO region(id, federal_district_id, title) VALUES(nextval('region_id_seq'), " . $districtId['federal_district_id'] .  ", N'Москва')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
    }
}
