<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417120025 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add a common stuff of communal services';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Электроснабжение')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Газоснабжение')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Водоотведение')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Горячее водоснабжение')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Холодное водоснабжение')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Отопление')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Лифтовое хозяйство')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Твёрдые коммунальные отходы')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Вентиляция и централизованное кондиционирование воздуха')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Домофон')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Состояние дорог')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Молниезащита зданий')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Системы защиты зданий и сооружений от пожара и пожарной безопасности')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Состояния конструкций зданий')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Уборка и санитарно-эпидемиологическая обработка мест общего пользования')");
        $this->addSql("INSERT INTO service_type(id, created_at, updated_at, title) VALUES (nextval('service_type_id_seq'), now()::timestamp, now()::timestamp, N'Уборка дорог и содержание придомовых территорий (благоустройство)')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("TRUNCATE service_type CASCADE;");
    }
}
