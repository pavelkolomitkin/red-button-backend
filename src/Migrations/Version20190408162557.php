<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408162557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE client_confirmation_key_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client_confirmation_key (id INT NOT NULL, client_id INT NOT NULL, key VARCHAR(255) NOT NULL, is_activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_548286B38A90ABA9 ON client_confirmation_key (key)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_548286B319EB6921 ON client_confirmation_key (client_id)');
        $this->addSql('ALTER TABLE client_confirmation_key ADD CONSTRAINT FK_548286B319EB6921 FOREIGN KEY (client_id) REFERENCES client_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD is_active BOOLEAN NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE client_confirmation_key_id_seq CASCADE');
        $this->addSql('DROP TABLE client_confirmation_key');
        $this->addSql('ALTER TABLE users DROP is_active');
    }
}
