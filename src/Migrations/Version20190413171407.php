<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190413171407 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE complaint_tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE region_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE issue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE complaint_confirmation_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE complaint_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE federal_district_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE complaint_confirmation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE complaint_tag (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62B0DF2F2B36786B ON complaint_tag (title)');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE region (id INT NOT NULL, federal_district_id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F62F176D23A6183 ON region (federal_district_id)');
        $this->addSql('CREATE TABLE issue (id INT NOT NULL, client_id INT NOT NULL, company_id INT DEFAULT NULL, region_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12AD233E19EB6921 ON issue (client_id)');
        $this->addSql('CREATE INDEX IDX_12AD233E979B1AD6 ON issue (company_id)');
        $this->addSql('CREATE INDEX IDX_12AD233E98260155 ON issue (region_id)');
        $this->addSql('CREATE TABLE complaint_confirmation_status (id INT NOT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(15) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE complaint (id INT NOT NULL, client_id INT NOT NULL, service_type_id INT DEFAULT NULL, region_id INT NOT NULL, message TEXT NOT NULL, latitude NUMERIC(12, 9) NOT NULL, longitude NUMERIC(12, 9) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F2732B519EB6921 ON complaint (client_id)');
        $this->addSql('CREATE INDEX IDX_5F2732B5AC8DE0F ON complaint (service_type_id)');
        $this->addSql('CREATE INDEX IDX_5F2732B598260155 ON complaint (region_id)');
        $this->addSql('CREATE TABLE complain_tag (complaint_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(complaint_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_FF554202EDAE188E ON complain_tag (complaint_id)');
        $this->addSql('CREATE INDEX IDX_FF554202BAD26311 ON complain_tag (tag_id)');
        $this->addSql('CREATE TABLE federal_district (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE complaint_confirmation (id INT NOT NULL, status_id INT NOT NULL, complaint_id INT NOT NULL, issue_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B7F0E4B46BF700BD ON complaint_confirmation (status_id)');
        $this->addSql('CREATE INDEX IDX_B7F0E4B4EDAE188E ON complaint_confirmation (complaint_id)');
        $this->addSql('CREATE INDEX IDX_B7F0E4B45E7AA58C ON complaint_confirmation (issue_id)');
        $this->addSql('CREATE TABLE service_type (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176D23A6183 FOREIGN KEY (federal_district_id) REFERENCES federal_district (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E19EB6921 FOREIGN KEY (client_id) REFERENCES client_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E98260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B519EB6921 FOREIGN KEY (client_id) REFERENCES client_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B5AC8DE0F FOREIGN KEY (service_type_id) REFERENCES service_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B598260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complain_tag ADD CONSTRAINT FK_FF554202EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complain_tag ADD CONSTRAINT FK_FF554202BAD26311 FOREIGN KEY (tag_id) REFERENCES complaint_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint_confirmation ADD CONSTRAINT FK_B7F0E4B46BF700BD FOREIGN KEY (status_id) REFERENCES complaint_confirmation_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint_confirmation ADD CONSTRAINT FK_B7F0E4B4EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint_confirmation ADD CONSTRAINT FK_B7F0E4B45E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complain_tag DROP CONSTRAINT FK_FF554202BAD26311');
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233E979B1AD6');
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233E98260155');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B598260155');
        $this->addSql('ALTER TABLE complaint_confirmation DROP CONSTRAINT FK_B7F0E4B45E7AA58C');
        $this->addSql('ALTER TABLE complaint_confirmation DROP CONSTRAINT FK_B7F0E4B46BF700BD');
        $this->addSql('ALTER TABLE complain_tag DROP CONSTRAINT FK_FF554202EDAE188E');
        $this->addSql('ALTER TABLE complaint_confirmation DROP CONSTRAINT FK_B7F0E4B4EDAE188E');
        $this->addSql('ALTER TABLE region DROP CONSTRAINT FK_F62F176D23A6183');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B5AC8DE0F');
        $this->addSql('DROP SEQUENCE complaint_tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE region_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE issue_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE complaint_confirmation_status_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE complaint_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE federal_district_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE complaint_confirmation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_type_id_seq CASCADE');
        $this->addSql('DROP TABLE complaint_tag');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE issue');
        $this->addSql('DROP TABLE complaint_confirmation_status');
        $this->addSql('DROP TABLE complaint');
        $this->addSql('DROP TABLE complain_tag');
        $this->addSql('DROP TABLE federal_district');
        $this->addSql('DROP TABLE complaint_confirmation');
        $this->addSql('DROP TABLE service_type');
    }
}
