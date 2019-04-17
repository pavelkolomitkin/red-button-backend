<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417115345 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE complaint ADD address_place_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_osm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_osm_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_display_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_road VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_village VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_state_district VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_post_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_county VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_state VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_country_code VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_name_details VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_latitude NUMERIC(12, 9) NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD address_longitude NUMERIC(12, 9) NOT NULL');
        $this->addSql('ALTER TABLE complaint DROP latitude');
        $this->addSql('ALTER TABLE complaint DROP longitude');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint ADD latitude NUMERIC(12, 9) NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD longitude NUMERIC(12, 9) NOT NULL');
        $this->addSql('ALTER TABLE complaint DROP address_place_id');
        $this->addSql('ALTER TABLE complaint DROP address_osm_id');
        $this->addSql('ALTER TABLE complaint DROP address_osm_type');
        $this->addSql('ALTER TABLE complaint DROP address_display_name');
        $this->addSql('ALTER TABLE complaint DROP address_road');
        $this->addSql('ALTER TABLE complaint DROP address_village');
        $this->addSql('ALTER TABLE complaint DROP address_state_district');
        $this->addSql('ALTER TABLE complaint DROP address_city');
        $this->addSql('ALTER TABLE complaint DROP address_post_code');
        $this->addSql('ALTER TABLE complaint DROP address_county');
        $this->addSql('ALTER TABLE complaint DROP address_state');
        $this->addSql('ALTER TABLE complaint DROP address_country');
        $this->addSql('ALTER TABLE complaint DROP address_country_code');
        $this->addSql('ALTER TABLE complaint DROP address_name_details');
        $this->addSql('ALTER TABLE complaint DROP address_latitude');
        $this->addSql('ALTER TABLE complaint DROP address_longitude');
    }
}
