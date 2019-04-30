<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430055605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO complaint_confirmation_status(id, title, code) VALUES(nextval('complaint_confirmation_status_id_seq'), 'Ожидает', 'pending')");
        $this->addSql("INSERT INTO complaint_confirmation_status(id, title, code) VALUES(nextval('complaint_confirmation_status_id_seq'), 'Подтвержден', 'confirmed')");
        $this->addSql("INSERT INTO complaint_confirmation_status(id, title, code) VALUES(nextval('complaint_confirmation_status_id_seq'), 'Отклонен', 'rejected')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
    }
}
