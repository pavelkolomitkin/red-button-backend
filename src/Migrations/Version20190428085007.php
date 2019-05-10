<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190428085007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE company_administrative_unit (company_id INT NOT NULL, administrative_unit_id INT NOT NULL, PRIMARY KEY(company_id, administrative_unit_id))');
        $this->addSql('CREATE INDEX IDX_9300F544979B1AD6 ON company_administrative_unit (company_id)');
        $this->addSql('CREATE INDEX IDX_9300F544E66451E1 ON company_administrative_unit (administrative_unit_id)');
        $this->addSql('CREATE TABLE administrative_unit_company (administrative_unit_id INT NOT NULL, company_id INT NOT NULL, PRIMARY KEY(administrative_unit_id, company_id))');
        $this->addSql('CREATE INDEX IDX_B845D468E66451E1 ON administrative_unit_company (administrative_unit_id)');
        $this->addSql('CREATE INDEX IDX_B845D468979B1AD6 ON administrative_unit_company (company_id)');
        $this->addSql('ALTER TABLE company_administrative_unit ADD CONSTRAINT FK_9300F544979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_administrative_unit ADD CONSTRAINT FK_9300F544E66451E1 FOREIGN KEY (administrative_unit_id) REFERENCES administrative_unit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE administrative_unit_company ADD CONSTRAINT FK_B845D468E66451E1 FOREIGN KEY (administrative_unit_id) REFERENCES administrative_unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE administrative_unit_company ADD CONSTRAINT FK_B845D468979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT fk_4fbf094fe66451e1');
        $this->addSql('DROP INDEX idx_4fbf094fe66451e1');
        $this->addSql('ALTER TABLE company DROP administrative_unit_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE company_administrative_unit');
        $this->addSql('DROP TABLE administrative_unit_company');
        $this->addSql('ALTER TABLE company ADD administrative_unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT fk_4fbf094fe66451e1 FOREIGN KEY (administrative_unit_id) REFERENCES administrative_unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_4fbf094fe66451e1 ON company (administrative_unit_id)');
    }
}
