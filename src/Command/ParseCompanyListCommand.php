<?php

namespace App\Command;

use App\Service\Parse\CompanyParser;
use App\Service\Parse\Exception\ParseException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ParseCompanyListCommand extends Command
{
    protected static $defaultName = 'app:parse-company-list';

    const REGIONS = [
//        "Белгородская область",
//        "Брянская область",
//        "Владимирская область",
//        "Воронежская область",
//        "Ивановская область",
//        "Калужская область",
//        "Костромская область",
//        "Курская область",
//        "Липецкая область",
//        "Московская область",
//        "Орловская область",
//        "Рязанская область",
//        "Смоленская область",
//        "Тамбовская область",
//        "Тверская область",
//        "Тульская область",
//        "Ярославская область",
//        "Белгородская область",
//        "Республика Карелия",
//        "Республика Коми",
//        "Архангельская область",
//        "Вологодская область",
//        "Калининградская область",
//        "Ленинградская область",
//        "Мурманская область",
//        "Новгородская область",
//        "Псковская область",
//        "Ненецкий автономный округ",
//        "Республика Адыгея",
//        "Краснодарский край",
//        "Астраханская область",
//        "Волгоградская область",
//        "Ростовская область",
//        "Республика Дагестан",
//        "Чеченская Республика",
//        "Удмуртская Республики",
//        "Чувашская Республики",
//        "Пермский край",
//        "Кировская область",
//        "Нижегородская область",
//        "Оренбургская область",
//        "Пензенская область",
//        "Самарская область",
//        "Ставропольский край",
//        "Саратовская область",
//        "Ульяновская область",
//        "Курганская область",
//        "Свердловская область",
//        "Тюменская область",
//        "Челябинская область",
//        "Ямало-Ненецкий автономный округ",
//        "Республика Алтай",
//        "Республика Бурятия",
//        "Республика Тыва",
//        "Республика Хакасия",
//        "Алтайский край",
//        "Забайкальский край",
//        "Красноярский край",
//        "Иркутская область",
//        "Кемеровская область",
//        "Новосибирская область",
//        "Омская область",
//        "Томская область",
//        "Республика Саха (Якутия)",
//        "Камчатский край",
//        "Приморский край",
//        "Хабаровский край",
//        "Амурская область",
//        "Магаданская область",
//        "Сахалинская область",
//        "Еврейская автономная область",

//        "Ханты-Мансийский автономный округ — Югра",
//        "Москва",
//        "Автономная Республика Крым",
//        "Санкт-Петербург",
//        "Севастополь",
//        "Башкортостан",
//        "Калмыкия",
//        "Кабардино-Балкария",
//        "Карачаево-Черкесия",
//        "Ингушетия",
//        "Марий Эл",
//        "Мордовия",
//        "Республика Северная Осетия — Алания",
//        "Татарстан",
//        "Чукотский автономный округ",
    ];

    /**
     * @var CompanyParser
     */
    private $parser;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param CompanyParser $parser
     *
     * @required
     */
    public function setCompanyParser(CompanyParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param EntityManagerInterface $entityManager
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Parse companies and their relative administrative units');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $progressBar = new ProgressBar($output);

        try
        {

            foreach (self::REGIONS as $regionName)
            {
                $output->writeln('Parsing of "' . $regionName . '"...');
                $this->parser->parse($regionName, function() use ($progressBar) {
                    $progressBar->advance();
                });
            }
        }
        catch (ParseException $exception)
        {
            $output->writeln($exception->getMessage());
        }
    }
}
