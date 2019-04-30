<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430102909 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE user_like_issue (issue_id INT NOT NULL, client_id INT NOT NULL, PRIMARY KEY(issue_id, client_id))');
        $this->addSql('CREATE INDEX IDX_2AECC355E7AA58C ON user_like_issue (issue_id)');
        $this->addSql('CREATE INDEX IDX_2AECC3519EB6921 ON user_like_issue (client_id)');
        $this->addSql('ALTER TABLE user_like_issue ADD CONSTRAINT FK_2AECC355E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_like_issue ADD CONSTRAINT FK_2AECC3519EB6921 FOREIGN KEY (client_id) REFERENCES client_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue ADD like_number INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE user_like_issue');
        $this->addSql('ALTER TABLE issue DROP like_number');
    }
}
