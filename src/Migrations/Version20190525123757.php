<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190525123757 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE region ADD osm_region_place_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD osm_region_osm_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD osm_region_osm_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD osm_region_display_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD osm_region_bounding_box TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD osm_region_geo_json JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD osm_region_latitude NUMERIC(12, 9) DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD osm_region_longitude NUMERIC(12, 9) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN region.osm_region_bounding_box IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE region DROP osm_region_place_id');
        $this->addSql('ALTER TABLE region DROP osm_region_osm_id');
        $this->addSql('ALTER TABLE region DROP osm_region_osm_type');
        $this->addSql('ALTER TABLE region DROP osm_region_display_name');
        $this->addSql('ALTER TABLE region DROP osm_region_bounding_box');
        $this->addSql('ALTER TABLE region DROP osm_region_geo_json');
        $this->addSql('ALTER TABLE region DROP osm_region_latitude');
        $this->addSql('ALTER TABLE region DROP osm_region_longitude');
    }
}
