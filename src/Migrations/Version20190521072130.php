<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190521072130 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->connection->exec("UPDATE region SET code='dagestan' WHERE title='Республика Дагестан'");
        $this->connection->exec("UPDATE region SET code='chechnya' WHERE title='Чеченская Республика'");
        $this->connection->exec("UPDATE region SET code='stavrapolsky_kray' WHERE title='Ставропольский край'");
        $this->connection->exec("UPDATE region SET code='kabardino_balkaria' WHERE title='Кабардино-Балкария'");
        $this->connection->exec("UPDATE region SET code='karachaevo_cherkesia' WHERE title='Карачаево-Черкесия'");
        $this->connection->exec("UPDATE region SET code='ingushetia' WHERE title='Ингушетия'");
        $this->connection->exec("UPDATE region SET code='severnay_osetia' WHERE title='Республика Северная Осетия — Алания'");


        $this->connection->exec("UPDATE region SET code='adigeya' WHERE title='Республика Адыгея'");
        $this->connection->exec("UPDATE region SET code='krasnodarsky_kray' WHERE title='Краснодарский край'");
        $this->connection->exec("UPDATE region SET code='astrahanskaya_oblast' WHERE title='Астраханская область'");
        $this->connection->exec("UPDATE region SET code='volgogradskaya_oblast' WHERE title='Волгоградская область'");
        $this->connection->exec("UPDATE region SET code='rostovskaya' WHERE title='Ростовская область'");
        $this->connection->exec("UPDATE region SET code='krim' WHERE title='Автономная Республика Крым'");
        $this->connection->exec("UPDATE region SET code='sevastopol' WHERE title='Севастополь'");
        $this->connection->exec("UPDATE region SET code='kalmikiya' WHERE title='Калмыкия'");

        $this->connection->exec("UPDATE region SET code='udmurskay_respublika' WHERE title='Удмуртская Республики'");
        $this->connection->exec("UPDATE region SET code='chuvashskaya_respublika' WHERE title='Чувашская Республики'");
        $this->connection->exec("UPDATE region SET code='permskiy_kray' WHERE title='Пермский край'");
        $this->connection->exec("UPDATE region SET code='kirovskaya_oblast' WHERE title='Кировская область'");
        $this->connection->exec("UPDATE region SET code='nizhegorodskaya_oblast' WHERE title='Нижегородская область'");
        $this->connection->exec("UPDATE region SET code='orienburgskaya_oblast' WHERE title='Оренбургская область'");
        $this->connection->exec("UPDATE region SET code='penzskaya_oblast' WHERE title='Пензенская область'");
        $this->connection->exec("UPDATE region SET code='samarskaya_oblast' WHERE title='Самарская область'");
        $this->connection->exec("UPDATE region SET code='saratovskaya_oblast' WHERE title='Саратовская область'");
        $this->connection->exec("UPDATE region SET code='ulyanovskaya_oblast' WHERE title='Ульяновская область'");
        $this->connection->exec("UPDATE region SET code='bashkortostan' WHERE title='Башкортостан'");
        $this->connection->exec("UPDATE region SET code='mariyel' WHERE title='Марий Эл'");
        $this->connection->exec("UPDATE region SET code='modovia' WHERE title='Мордовия'");
        $this->connection->exec("UPDATE region SET code='tatarstan' WHERE title='Татарстан'");



        $this->connection->exec("UPDATE region SET code='belgorodskaya_oblast' WHERE title='Белгородская область'");
        $this->connection->exec("UPDATE region SET code='bryanskaya_oblast' WHERE title='Брянская область'");
        $this->connection->exec("UPDATE region SET code='vladimirskaya_oblast' WHERE title='Владимирская область'");
        $this->connection->exec("UPDATE region SET code='voronezhskaya_oblast' WHERE title='Воронежская область'");
        $this->connection->exec("UPDATE region SET code='ivanovskaya_oblast' WHERE title='Ивановская область'");
        $this->connection->exec("UPDATE region SET code='kaluzhskaya_oblast' WHERE title='Калужская область'");
        $this->connection->exec("UPDATE region SET code='kostromskaya_oblast' WHERE title='Костромская область'");
        $this->connection->exec("UPDATE region SET code='kurskaya_oblast' WHERE title='Курская область'");
        $this->connection->exec("UPDATE region SET code='lipetskaya_oblast' WHERE title='Липецкая область'");
        $this->connection->exec("UPDATE region SET code='moskovskaya_oblast' WHERE title='Московская область'");
        $this->connection->exec("UPDATE region SET code='orlovskaya_oblast' WHERE title='Орловская область'");
        $this->connection->exec("UPDATE region SET code='ryazanskaya_oblast' WHERE title='Рязанская область'");
        $this->connection->exec("UPDATE region SET code='smolenskaya_oblast' WHERE title='Смоленская область'");
        $this->connection->exec("UPDATE region SET code='tambovskaya_oblast' WHERE title='Тамбовская область'");
        $this->connection->exec("UPDATE region SET code='tverskaya_oblast' WHERE title='Тверская область'");
        $this->connection->exec("UPDATE region SET code='tulskaya_oblast' WHERE title='Тульская область'");
        $this->connection->exec("UPDATE region SET code='yaroslavskaya_oblast' WHERE title='Ярославская область'");
        $this->connection->exec("UPDATE region SET code='belgorodskaya_oblast' WHERE title='Белгородская область'");
        $this->connection->exec("UPDATE region SET code='moskva' WHERE title='Москва'");


        $this->connection->exec("UPDATE region SET code='karelia' WHERE title='Республика Карелия'");
        $this->connection->exec("UPDATE region SET code='komi' WHERE title='Республика Коми'");
        $this->connection->exec("UPDATE region SET code='arhangelskaya_oblast' WHERE title='Архангельская область'");
        $this->connection->exec("UPDATE region SET code='vologodskaya_oblast' WHERE title='Вологодская область'");
        $this->connection->exec("UPDATE region SET code='kaliningradskaya_oblast' WHERE title='Калининградская область'");
        $this->connection->exec("UPDATE region SET code='leningradskaya_oblast' WHERE title='Ленинградская область'");
        $this->connection->exec("UPDATE region SET code='murmanskaya_oblast' WHERE title='Мурманская область'");
        $this->connection->exec("UPDATE region SET code='novgorodskaya_oblast' WHERE title='Новгородская область'");
        $this->connection->exec("UPDATE region SET code='pskovskaya_oblast' WHERE title='Псковская область'");
        $this->connection->exec("UPDATE region SET code='nenezkiy_ao' WHERE title='Ненецкий автономный округ'");
        $this->connection->exec("UPDATE region SET code='piter' WHERE title='Санкт-Петербург'");


        $this->connection->exec("UPDATE region SET code='kurganskaya_oblast' WHERE title='Курганская область'");
        $this->connection->exec("UPDATE region SET code='sverdlovskaya_oblast' WHERE title='Свердловская область'");
        $this->connection->exec("UPDATE region SET code='tumenskaya_oblast' WHERE title='Тюменская область'");
        $this->connection->exec("UPDATE region SET code='chelabinskaya_oblast' WHERE title='Челябинская область'");
        $this->connection->exec("UPDATE region SET code='yalmo_nenezkiy_ao' WHERE title='Ямало-Ненецкий автономный округ'");
        $this->connection->exec("UPDATE region SET code='hanti_mansiyskiy_ao' WHERE title='Ханты-Мансийский автономный округ — Югра'");



        $this->connection->exec("UPDATE region SET code='respublika_altay' WHERE title='Республика Алтай'");
        $this->connection->exec("UPDATE region SET code='buryatia' WHERE title='Республика Бурятия'");
        $this->connection->exec("UPDATE region SET code='tiva' WHERE title='Республика Тыва'");
        $this->connection->exec("UPDATE region SET code='hakasia' WHERE title='Республика Хакасия'");
        $this->connection->exec("UPDATE region SET code='altayskiy_kray' WHERE title='Алтайский край'");
        $this->connection->exec("UPDATE region SET code='zabaikalskiy_kray' WHERE title='Забайкальский край'");
        $this->connection->exec("UPDATE region SET code='krasnoyarskiy_kray' WHERE title='Красноярский край'");
        $this->connection->exec("UPDATE region SET code='irkutskaya_oblast' WHERE title='Иркутская область'");
        $this->connection->exec("UPDATE region SET code='kemerovskaya_oblast' WHERE title='Кемеровская область'");
        $this->connection->exec("UPDATE region SET code='novosibirskaya_oblast' WHERE title='Новосибирская область'");
        $this->connection->exec("UPDATE region SET code='omskaya_oblast' WHERE title='Омская область'");
        $this->connection->exec("UPDATE region SET code='tomskaya_oblast' WHERE title='Томская область'");


        $this->connection->exec("UPDATE region SET code='saha_yakutia' WHERE title='Республика Саха (Якутия)'");
        $this->connection->exec("UPDATE region SET code='kamchatskiy_kray' WHERE title='Камчатский край'");
        $this->connection->exec("UPDATE region SET code='primorskiy_kray' WHERE title='Приморский край'");
        $this->connection->exec("UPDATE region SET code='habarovsky_kray' WHERE title='Хабаровский край'");
        $this->connection->exec("UPDATE region SET code='amurskaya_oblast' WHERE title='Амурская область'");
        $this->connection->exec("UPDATE region SET code='magadanskaya_oblast' WHERE title='Магаданская область'");
        $this->connection->exec("UPDATE region SET code='sahalinskaya_oblast' WHERE title='Сахалинская область'");
        $this->connection->exec("UPDATE region SET code='evreyskaya_ao' WHERE title='Еврейская автономная область'");
        $this->connection->exec("UPDATE region SET code='chukotskiy_ao' WHERE title='Чукотский автономный округ'");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
    }
}
