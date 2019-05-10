<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190507133649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE issue ADD comment_number INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE complaint_confirmation DROP CONSTRAINT FK_B7F0E4B45E7AA58C');
        $this->addSql('ALTER TABLE complaint_confirmation ADD CONSTRAINT FK_B7F0E4B45E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE complaint_confirmation DROP CONSTRAINT fk_b7f0e4b45e7aa58c');
        $this->addSql('ALTER TABLE complaint_confirmation ADD CONSTRAINT fk_b7f0e4b45e7aa58c FOREIGN KEY (issue_id) REFERENCES issue (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue DROP comment_number');
    }
}
