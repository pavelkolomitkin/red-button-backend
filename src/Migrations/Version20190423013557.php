<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423013557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE region SET title = N'Автономная Республика Крым' WHERE title = N'Республика Крым'");
        $this->addSql("UPDATE region SET title = N'Санкт-Петербург' WHERE title = N'город Санкт-Петербург'");
        $this->addSql("UPDATE region SET title = N'Севастополь' WHERE title = N'город Севастополь'");
        $this->addSql("UPDATE region SET title = N'Башкортостан' WHERE title = N'Республика Башкортостан'");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
