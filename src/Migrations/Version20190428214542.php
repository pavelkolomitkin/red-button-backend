<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190428214542 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE issue ADD service_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD message TEXT NOT NULL');
        $this->addSql('ALTER TABLE issue ADD address_place_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_osm_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_osm_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_display_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_road VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_village VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_state_district VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_post_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_county VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_state VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_country_code VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_name_details VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD address_latitude NUMERIC(12, 9) NOT NULL');
        $this->addSql('ALTER TABLE issue ADD address_longitude NUMERIC(12, 9) NOT NULL');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233EAC8DE0F FOREIGN KEY (service_type_id) REFERENCES service_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_12AD233EAC8DE0F ON issue (service_type_id)');
        $this->addSql('ALTER TABLE issue_picture ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE issue_picture ALTER issue_id DROP NOT NULL');
        $this->addSql('ALTER TABLE issue_picture ADD CONSTRAINT FK_B3230A687E3C61F9 FOREIGN KEY (owner_id) REFERENCES client_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B3230A687E3C61F9 ON issue_picture (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233EAC8DE0F');
        $this->addSql('DROP INDEX IDX_12AD233EAC8DE0F');
        $this->addSql('ALTER TABLE issue DROP service_type_id');
        $this->addSql('ALTER TABLE issue DROP message');
        $this->addSql('ALTER TABLE issue DROP address_place_id');
        $this->addSql('ALTER TABLE issue DROP address_osm_id');
        $this->addSql('ALTER TABLE issue DROP address_osm_type');
        $this->addSql('ALTER TABLE issue DROP address_display_name');
        $this->addSql('ALTER TABLE issue DROP address_road');
        $this->addSql('ALTER TABLE issue DROP address_village');
        $this->addSql('ALTER TABLE issue DROP address_state_district');
        $this->addSql('ALTER TABLE issue DROP address_city');
        $this->addSql('ALTER TABLE issue DROP address_post_code');
        $this->addSql('ALTER TABLE issue DROP address_county');
        $this->addSql('ALTER TABLE issue DROP address_state');
        $this->addSql('ALTER TABLE issue DROP address_country');
        $this->addSql('ALTER TABLE issue DROP address_country_code');
        $this->addSql('ALTER TABLE issue DROP address_name_details');
        $this->addSql('ALTER TABLE issue DROP address_latitude');
        $this->addSql('ALTER TABLE issue DROP address_longitude');
        $this->addSql('ALTER TABLE issue_picture DROP CONSTRAINT FK_B3230A687E3C61F9');
        $this->addSql('DROP INDEX IDX_B3230A687E3C61F9');
        $this->addSql('ALTER TABLE issue_picture DROP owner_id');
        $this->addSql('ALTER TABLE issue_picture ALTER issue_id SET NOT NULL');
    }
}
