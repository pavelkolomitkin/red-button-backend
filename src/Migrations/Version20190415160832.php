<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415160832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE complaint_picture ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE complaint_picture ADD CONSTRAINT FK_6393AFD87E3C61F9 FOREIGN KEY (owner_id) REFERENCES client_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6393AFD87E3C61F9 ON complaint_picture (owner_id)');
        $this->addSql('ALTER TABLE video_material ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE video_material ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE video_material ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE video_material ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE video_material ADD CONSTRAINT FK_D43C47297E3C61F9 FOREIGN KEY (owner_id) REFERENCES client_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D43C47297E3C61F9 ON video_material (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint_picture DROP CONSTRAINT FK_6393AFD87E3C61F9');
        $this->addSql('DROP INDEX IDX_6393AFD87E3C61F9');
        $this->addSql('ALTER TABLE complaint_picture DROP owner_id');
        $this->addSql('ALTER TABLE video_material DROP CONSTRAINT FK_D43C47297E3C61F9');
        $this->addSql('DROP INDEX IDX_D43C47297E3C61F9');
        $this->addSql('ALTER TABLE video_material DROP owner_id');
        $this->addSql('ALTER TABLE video_material DROP created_at');
        $this->addSql('ALTER TABLE video_material DROP updated_at');
        $this->addSql('ALTER TABLE video_material DROP deleted_at');
    }
}
