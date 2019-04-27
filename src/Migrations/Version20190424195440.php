<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190424195440 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE complaint_complaint_tag (complaint_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(complaint_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_812C19CBEDAE188E ON complaint_complaint_tag (complaint_id)');
        $this->addSql('CREATE INDEX IDX_812C19CBBAD26311 ON complaint_complaint_tag (tag_id)');
        $this->addSql('ALTER TABLE complaint_complaint_tag ADD CONSTRAINT FK_812C19CBEDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint_complaint_tag ADD CONSTRAINT FK_812C19CBBAD26311 FOREIGN KEY (tag_id) REFERENCES complaint_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE complain_tag');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE complain_tag (complaint_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(complaint_id, tag_id))');
        $this->addSql('CREATE INDEX idx_ff554202bad26311 ON complain_tag (tag_id)');
        $this->addSql('CREATE INDEX idx_ff554202edae188e ON complain_tag (complaint_id)');
        $this->addSql('ALTER TABLE complain_tag ADD CONSTRAINT fk_ff554202edae188e FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complain_tag ADD CONSTRAINT fk_ff554202bad26311 FOREIGN KEY (tag_id) REFERENCES complaint_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE complaint_complaint_tag');
    }
}
