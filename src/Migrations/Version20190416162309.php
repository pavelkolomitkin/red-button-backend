<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416162309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Importing regions';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->connection->exec("
            INSERT INTO region (id, title, federal_district_id) VALUES (1,N'Белгородская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (2,N'Брянская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (3,N'Владимирская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (4,N'Воронежская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (5,N'Ивановская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (6,N'Калужская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (7,N'Костромская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (8,N'Курская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (9,N'Липецкая область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (10,N'Московская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (11,N'Орловская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (12,N'Рязанская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (13,N'Смоленская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (14,N'Тамбовская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (15,N'Тверская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (16,N'Тульская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (17,N'Ярославская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (18,N'город Москва',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (19,N'Белгородская область',1);
            INSERT INTO region (id, title, federal_district_id) VALUES (20,N'Республика Карелия',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (21,N'Республика Коми',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (22,N'Архангельская область',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (23,N'Вологодская область',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (24,N'Калининградская область',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (25,N'Ленинградская область',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (26,N'Мурманская область',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (27,N'Новгородская область',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (28,N'Псковская область',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (29,N'город Санкт-Петербург',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (30,N'Ненецкий автономный округ',2);
            INSERT INTO region (id, title, federal_district_id) VALUES (31,N'Республика Адыгея',3);
            INSERT INTO region (id, title, federal_district_id) VALUES (32,N'Республика Калмыкия',3);
            INSERT INTO region (id, title, federal_district_id) VALUES (33,N'Краснодарский край',3);
            INSERT INTO region (id, title, federal_district_id) VALUES (34,N'Астраханская область',3);
            INSERT INTO region (id, title, federal_district_id) VALUES (35,N'Волгоградская область',3);
            INSERT INTO region (id, title, federal_district_id) VALUES (36,N'Ростовская область',3);
            INSERT INTO region (id, title, federal_district_id) VALUES (38,N'Республика Дагестан',4);
            INSERT INTO region (id, title, federal_district_id) VALUES (39,N'Республика Ингушетии',4);
            INSERT INTO region (id, title, federal_district_id) VALUES (40,N'Кабардино-Балкарская Республика',4);
            INSERT INTO region (id, title, federal_district_id) VALUES (41,N'Карачаево-Черкесская Республика',4);
            INSERT INTO region (id, title, federal_district_id) VALUES (42,N'Республика Северная Осетия-Алания',4);
            INSERT INTO region (id, title, federal_district_id) VALUES (43,N'Чеченская Республика',4);
            INSERT INTO region (id, title, federal_district_id) VALUES (44,N'Ставропольский край',4);
            INSERT INTO region (id, title, federal_district_id) VALUES (45,N'Республика Башкортостан',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (46,N'Республика Марий Эл',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (47,N'Республика Мордовии',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (48,N'Республика Татарстан',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (49,N'Удмуртская Республики',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (50,N'Чувашская Республики',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (51,N'Пермский край',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (52,N'Кировская область',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (53,N'Нижегородская область',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (54,N'Оренбургская область',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (55,N'Пензенская область',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (56,N'Самарская область',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (57,N'Саратовская область',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (58,N'Ульяновская область',5);
            INSERT INTO region (id, title, federal_district_id) VALUES (59,N'Курганская область',6);
            INSERT INTO region (id, title, federal_district_id) VALUES (60,N'Свердловская область',6);
            INSERT INTO region (id, title, federal_district_id) VALUES (61,N'Тюменская область',6);
            INSERT INTO region (id, title, federal_district_id) VALUES (62,N'Челябинская область',6);
            INSERT INTO region (id, title, federal_district_id) VALUES (63,N'Ханты-Мансийский автономный округ - Югра',6);
            INSERT INTO region (id, title, federal_district_id) VALUES (64,N'Ямало-Ненецкий автономный округ',6);
            INSERT INTO region (id, title, federal_district_id) VALUES (65,N'Республика Алтай',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (66,N'Республика Бурятия',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (67,N'Республика Тыва',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (68,N'Республика Хакасия',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (69,N'Алтайский край',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (70,N'Забайкальский край',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (71,N'Красноярский край',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (72,N'Иркутская область',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (73,N'Кемеровская область',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (74,N'Новосибирская область',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (75,N'Омская область',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (76,N'Томская область',7);
            INSERT INTO region (id, title, federal_district_id) VALUES (77,N'Республика Саха (Якутия)',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (78,N'Камчатский край',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (79,N'Приморский край',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (80,N'Хабаровский край',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (81,N'Амурская область',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (82,N'Магаданская область',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (83,N'Сахалинская область',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (84,N'Еврейская автономная область',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (85,N'Чукотский автономный округ',8);
            INSERT INTO region (id, title, federal_district_id) VALUES (86,N'Республика Крым',3);
            INSERT INTO region (id, title, federal_district_id) VALUES (87,N'город Севастополь',3);
            ");
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->connection->executeQuery("TRUNCATE region CASCADE;");
    }
}
