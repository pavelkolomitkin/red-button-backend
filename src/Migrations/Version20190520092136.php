<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190520092136 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->connection->exec("UPDATE service_type SET code='electrosnab' WHERE title='Электроснабжение'");
        $this->connection->exec("UPDATE service_type SET code='gasosnab' WHERE title='Газоснабжение'");
        $this->connection->exec("UPDATE service_type SET code='vodootvedenie' WHERE title='Водоотведение'");
        $this->connection->exec("UPDATE service_type SET code='gorvodsnab' WHERE title='Горячее водоснабжение'");
        $this->connection->exec("UPDATE service_type SET code='holvodsnab' WHERE title='Холодное водоснабжение'");
        $this->connection->exec("UPDATE service_type SET code='otoplenie' WHERE title='Отопление'");
        $this->connection->exec("UPDATE service_type SET code='lift' WHERE title='Лифтовое хозяйство'");
        $this->connection->exec("UPDATE service_type SET code='tvcomothody' WHERE title='Твёрдые коммунальные отходы'");
        $this->connection->exec("UPDATE service_type SET code='ventilazia' WHERE title='Вентиляция и централизованное кондиционирование воздуха'");
        $this->connection->exec("UPDATE service_type SET code='domofon' WHERE title='Домофон'");
        $this->connection->exec("UPDATE service_type SET code='dorogi' WHERE title='Состояние дорог'");
        $this->connection->exec("UPDATE service_type SET code='molnezashita' WHERE title='Молниезащита зданий'");
        $this->connection->exec("UPDATE service_type SET code='pozharobezopasnost' WHERE title='Системы защиты зданий и сооружений от пожара и пожарной безопасности'");
        $this->connection->exec("UPDATE service_type SET code='sostojaniezdaniy' WHERE title='Состояния конструкций зданий'");
        $this->connection->exec("UPDATE service_type SET code='uborkamest' WHERE title='Уборка и санитарно-эпидемиологическая обработка мест общего пользования'");
        $this->connection->exec("UPDATE service_type SET code='uborkauliz' WHERE title='Уборка дорог и содержание придомовых территорий (благоустройство)'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
