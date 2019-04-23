<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423123059 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("UPDATE region SET title = N'Калмыкия' WHERE title = N'Республика Калмыкия'");
        $this->addSql("UPDATE region SET title = N'Кабардино-Балкария' WHERE title = N'Кабардино-Балкарская Республика'");
        $this->addSql("UPDATE region SET title = N'Карачаево-Черкесия' WHERE title = N'Карачаево-Черкесская Республика'");
        $this->addSql("UPDATE region SET title = N'Ингушетия' WHERE title = N'Республика Ингушетии'");
        $this->addSql("UPDATE region SET title = N'Марий Эл' WHERE title = N'Республика Марий Эл'");
        $this->addSql("UPDATE region SET title = N'Мордовия' WHERE title = N'Республика Мордовии'");
        $this->addSql("UPDATE region SET title = N'Республика Северная Осетия — Алания' WHERE title = N'Республика Северная Осетия-Алания'");
        $this->addSql("UPDATE region SET title = N'Татарстан' WHERE title = N'Республика Татарстан'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
