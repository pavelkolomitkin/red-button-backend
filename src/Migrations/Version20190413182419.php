<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190413182419 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE complaint_picture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE issue_picture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE complaint_picture (id INT NOT NULL, complaint_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6393AFD8EDAE188E ON complaint_picture (complaint_id)');
        $this->addSql('COMMENT ON COLUMN complaint_picture.image_dimensions IS \'(DC2Type:simple_array)\'');
        $this->addSql('CREATE TABLE issue_picture (id INT NOT NULL, issue_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3230A685E7AA58C ON issue_picture (issue_id)');
        $this->addSql('COMMENT ON COLUMN issue_picture.image_dimensions IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE complaint_picture ADD CONSTRAINT FK_6393AFD8EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue_picture ADD CONSTRAINT FK_B3230A685E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint_tag ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE complaint_tag ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE company ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE company ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE company ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE issue ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE issue ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint_confirmation ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE complaint_confirmation ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE complaint_confirmation ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE service_type ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE service_type ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE complaint_picture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE issue_picture_id_seq CASCADE');
        $this->addSql('DROP TABLE complaint_picture');
        $this->addSql('DROP TABLE issue_picture');
        $this->addSql('ALTER TABLE complaint_tag DROP created_at');
        $this->addSql('ALTER TABLE complaint_tag DROP updated_at');
        $this->addSql('ALTER TABLE company DROP created_at');
        $this->addSql('ALTER TABLE company DROP updated_at');
        $this->addSql('ALTER TABLE company DROP deleted_at');
        $this->addSql('ALTER TABLE service_type DROP created_at');
        $this->addSql('ALTER TABLE service_type DROP updated_at');
        $this->addSql('ALTER TABLE complaint DROP created_at');
        $this->addSql('ALTER TABLE complaint DROP updated_at');
        $this->addSql('ALTER TABLE complaint DROP deleted_at');
        $this->addSql('ALTER TABLE issue DROP created_at');
        $this->addSql('ALTER TABLE issue DROP updated_at');
        $this->addSql('ALTER TABLE issue DROP deleted_at');
        $this->addSql('ALTER TABLE complaint_confirmation DROP created_at');
        $this->addSql('ALTER TABLE complaint_confirmation DROP updated_at');
        $this->addSql('ALTER TABLE complaint_confirmation DROP deleted_at');
    }
}
