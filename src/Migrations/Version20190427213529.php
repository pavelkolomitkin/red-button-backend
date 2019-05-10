<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190427213529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE company_legal_form_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE administrative_unit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE company_legal_form (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C27DFA6E2B36786B ON company_legal_form (title)');
        $this->addSql('CREATE TABLE administrative_unit (id INT NOT NULL, region_id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C86D61BE98260155 ON administrative_unit (region_id)');
        $this->addSql('ALTER TABLE administrative_unit ADD CONSTRAINT FK_C86D61BE98260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD legal_form_id INT NOT NULL');
        $this->addSql('ALTER TABLE company ADD administrative_unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE company ADD full_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE company ADD head_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD inn VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD ogrn VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD legal_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD actual_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD postal_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD phone_numbers VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD office_hours VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD site VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD building_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD surface NUMERIC(12, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F98CD0513 FOREIGN KEY (legal_form_id) REFERENCES company_legal_form (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FE66451E1 FOREIGN KEY (administrative_unit_id) REFERENCES administrative_unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4FBF094F98CD0513 ON company (legal_form_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FE66451E1 ON company (administrative_unit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F98CD0513');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094FE66451E1');
        $this->addSql('DROP SEQUENCE company_legal_form_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE administrative_unit_id_seq CASCADE');
        $this->addSql('DROP TABLE company_legal_form');
        $this->addSql('DROP TABLE administrative_unit');
        $this->addSql('DROP INDEX IDX_4FBF094F98CD0513');
        $this->addSql('DROP INDEX IDX_4FBF094FE66451E1');
        $this->addSql('ALTER TABLE company DROP legal_form_id');
        $this->addSql('ALTER TABLE company DROP administrative_unit_id');
        $this->addSql('ALTER TABLE company DROP full_name');
        $this->addSql('ALTER TABLE company DROP head_name');
        $this->addSql('ALTER TABLE company DROP inn');
        $this->addSql('ALTER TABLE company DROP ogrn');
        $this->addSql('ALTER TABLE company DROP legal_address');
        $this->addSql('ALTER TABLE company DROP actual_address');
        $this->addSql('ALTER TABLE company DROP postal_address');
        $this->addSql('ALTER TABLE company DROP phone_numbers');
        $this->addSql('ALTER TABLE company DROP office_hours');
        $this->addSql('ALTER TABLE company DROP email');
        $this->addSql('ALTER TABLE company DROP site');
        $this->addSql('ALTER TABLE company DROP building_number');
        $this->addSql('ALTER TABLE company DROP surface');
    }
}
