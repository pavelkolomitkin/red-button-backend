<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415134441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE video_material_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE video_material (id INT NOT NULL, complaint_id INT DEFAULT NULL, issue_id INT DEFAULT NULL, originalLink VARCHAR(255) NOT NULL, meta_data JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D43C4729EDAE188E ON video_material (complaint_id)');
        $this->addSql('CREATE INDEX IDX_D43C47295E7AA58C ON video_material (issue_id)');
        $this->addSql('ALTER TABLE video_material ADD CONSTRAINT FK_D43C4729EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_material ADD CONSTRAINT FK_D43C47295E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE video_material_id_seq CASCADE');
        $this->addSql('DROP TABLE video_material');
    }
}
