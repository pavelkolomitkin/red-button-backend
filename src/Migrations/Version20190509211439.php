<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509211439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE company DROP CONSTRAINT fk_4fbf094f98cd0513');
        $this->addSql('DROP SEQUENCE company_legal_form_id_seq CASCADE');
        $this->addSql('DROP TABLE company_legal_form');
        $this->addSql('DROP INDEX idx_4fbf094f98cd0513');
        $this->addSql('ALTER TABLE company DROP legal_form_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE company_legal_form_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE company_legal_form (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_c27dfa6e2b36786b ON company_legal_form (title)');
        $this->addSql('ALTER TABLE company ADD legal_form_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT fk_4fbf094f98cd0513 FOREIGN KEY (legal_form_id) REFERENCES company_legal_form (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_4fbf094f98cd0513 ON company (legal_form_id)');
    }
}
