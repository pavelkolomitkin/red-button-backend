<?php

namespace App\Service\Parse;

use App\Entity\AdministrativeUnit;
use App\Entity\Company;
use App\Entity\Region;
use App\Repository\AdministrativeUnitRepository;
use App\Repository\CompanyRepository;
use App\Service\Parse\Exception\ParseException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use function GuzzleHttp\Promise\is_settled;
use phpDocumentor\Reflection\Types\Callable_;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompanyParser
 * @package App\Service\Parse
 */
class CompanyParser
{
    const COMPANY_REMOTE_FIELD_MAP = [
        'Фирменное наименование юридического лица (согласно уставу организации)' => 'fullName',
        'Организационно-правовая форма' => 'legalFormText',
        'ФИО руководителя' => 'headName',
        'Идентификационный номер налогоплательщика (ИНН)' => 'INN',
        'Основной государственный регистрационный номер / основной государственный регистрационный' => 'OGRN',
        'Место государственной регистрации юридического лица (адрес юридического лица)' => 'legalAddress',
        'Адрес фактического местонахождения органов управления' => 'actualAddress',
        'Почтовый адрес' => 'postalAddress',
        'Режим работы, в т. ч. часы личного приема граждан' => 'officeHours',
        'Контактные телефоны' => 'phoneNumbers',
        'Адрес электронной почты (при наличии)' => 'email',
        'Официальный сайт в сети Интернет (при наличии)' => 'site',
        'Количество домов, находящихся в управлении , ед.' => 'buildingNumber',
        'Площадь домов, находящихся в управлении, кв. м' => 'surface',

    ];

    const REGION_REMOTE_NAME_MAP = [
        'Кабардино-Балкарская Республика' => 'Кабардино-Балкария',
        'Карачаево-Черкесская Республика' => 'Карачаево-Черкесия',
        'Ненецкий АО' => 'Ненецкий автономный округ',
        'Республика Башкортостан' => 'Башкортостан',
        'Республика Ингушетия' => 'Ингушетия',
        'Республика Калмыкия' => 'Калмыкия',
        'Республика Крым' => 'Автономная Республика Крым',
        'Республика Марий Эл' => 'Марий Эл',
        'Республика Мордовия' => 'Мордовия',
        'Республика Татарстан' => 'Татарстан',
        'Удмуртская Республика' => 'Удмуртская Республики',
        'Ханты-Мансийский АО — Югра' => 'Ханты-Мансийский автономный округ — Югра',
        'Чувашская Республика' => 'Чувашская Республики',
        'Чукотский АО' => 'Чукотский автономный округ',
        'Ямало-Ненецкий АО' => 'Ямало-Ненецкий автономный округ',
    ];

    const BASE_REMOTE_URL = 'http://www.zhkh.su';

    const REGIONS_REMOTE_URL = self::BASE_REMOTE_URL . '/upravljajushhie_kompanii_tszh_i_zhsk_rossii/';

    private $httpClient;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->httpClient = new Client();
        $this->entityManager = $entityManager;
    }

    public function parse($regionName, Callable $progressCallback = null)
    {
        $remoteUrl = $this->getRemoteRegionLink($regionName);
        $region = $this->entityManager->getRepository('App\Entity\Region')->findOneBy(['title' => $regionName]);

        $units = $this->getAdministrativeUnits($region, $remoteUrl);
        foreach ($units as $unitRemoteLink => $unit)
        {
            $this->getCompanies($unit, $unitRemoteLink);

            if ($progressCallback !== null)
            {
                $progressCallback();
            }
        }

        $this->entityManager->flush();
        gc_collect_cycles();
    }

    /**
     * @return array['remoteLink' => $region]
     * @throws ParseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getRegions()
    {
        $result = [];

        $repository = $this->entityManager->getRepository('App\Entity\Region');

        $remoteRegions = $this->getRegionsFromRemote();
        foreach ($remoteRegions as $regionLink)
        {
            // find it in local database
            $region = $repository->findOneBy(['title' => $regionLink['name']]);
            // if it hasn't been found
            if (!$region) {
                // thrown new Exception with region name
                throw new ParseException('Can not find region with name "' . $regionLink['name'] . '"');
            }

            $result[$regionLink['link']] = $region;
        }

        return $result;
    }

    private function getRemoteRegionLink($regionName)
    {
        $remoteRegions = $this->getRegionsFromRemote();

        $result = null;
        foreach ($remoteRegions as $item)
        {
            if ($regionName === $item['name'])
            {
                $result = $item['link'];
                break;
            }
        }

        return $result;
    }

    /**
     * @return array['name', 'link']
     * @throws ParseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getRegionsFromRemote()
    {
        $result = [];

        $response = $this->httpClient->request('GET', self::REGIONS_REMOTE_URL);
        if ($response->getStatusCode() !== 200)
        {
            throw new ParseException('Could not get contents from the regions URL: "' . self::REGIONS_REMOTE_URL . '"');
        }

        $crawler = new Crawler($response->getBody()->getContents());

        $crawler->filter('ul.regions-list li a')
            ->each(function(Crawler $node, $index) use (&$result) {


                $link = $node->attr('href');

                $regionName = $node->text();
                $lengthName = mb_strpos($regionName, 'Количество');
                $regionName = mb_substr($regionName, 0, $lengthName);

                $regionName = $this->getLocalRegionName($regionName);

                $result[] = [
                    'name' => $regionName,
                    'link' => $link
                ];

                /**
                <a href="/upravljajushhie_kompanii_tszh_i_zhsk_rossii/altajskij_kraj/">
                    <img src="/images/regions/region22.png" width="48" height="48" alt="">
                    Алтайский край
                    <span>
                        <span>Количество компаний: 796</span>
                    </span>
                </a>
                 *
                 */

            });

        return $result;
    }

    public function getLocalRegionName(string $remoteName)
    {
        return isset(self::REGION_REMOTE_NAME_MAP[$remoteName]) ? self::REGION_REMOTE_NAME_MAP[$remoteName] : $remoteName;
    }

    public function getRemoteRegionName(string $localName)
    {
        $map = array_flip(self::REGION_REMOTE_NAME_MAP);
        return isset($map[$localName]) ? $map[$localName] : $localName;
    }

    /**
     * @param Region $region
     * @param string $remoteRegionLink
     * @return array['remoteLink' => $administrativeUnit]
     * @throws ParseException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getAdministrativeUnits(Region $region, string $remoteRegionLink)
    {
        $result = [];


        /** @var AdministrativeUnitRepository $repository */
        $repository = $this->entityManager->getRepository('App\Entity\AdministrativeUnit');

        $remoteAdministrativeUnits = $this->getRegionAdministrativeUnitsFromRemote($remoteRegionLink);
        foreach ($remoteAdministrativeUnits as $remoteAdministrativeUnit)
        {
            $unit = $repository->findOneBy([
                'title' => $remoteAdministrativeUnit['name'],
                'region' => $region
            ]);
            if (!$unit)
            {
                $unit = new AdministrativeUnit();
                $unit
                    ->setRegion($region)
                    ->setTitle($remoteAdministrativeUnit['name']);

                $this->entityManager->persist($unit);
                $this->entityManager->flush($unit);
            }

            if ($remoteAdministrativeUnit['link'] !== null)
            {
                $result[$remoteAdministrativeUnit['link']] = $unit;
            }
        }

        return $result;
    }

    /**
     * @param $link
     * @return array['name', 'link']
     * @throws ParseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getRegionAdministrativeUnitsFromRemote($link)
    {
        $result = [];

        $absoluteUrl =  $this->getAbsoluteUrl($link);

        $response = $this->httpClient->request('GET', $absoluteUrl);
        if ($response->getStatusCode() !== 200)
        {
            throw new ParseException('Could not get contents from the region URL: "' . $link . '"');
        }

        $crawler = new Crawler($response->getBody()->getContents());

        $crawler->filter('div.regions-mun-list ul li div')
            ->each(function(Crawler $node, $index) use (&$result) {

                $item = [
                    'link' => null,
                ];

                if ($node->filter('a')->count() > 0)
                {
                    $node = $node->filter('a');

                    $item['link'] = $node->attr('href');
                }

                $item['name'] = trim($node->text());

                $result[] = $item;

            });

        return $result;
    }

    private function getCompanies(AdministrativeUnit $administrativeUnit, string $remoteUnitLink)
    {
        $result = [];

        try {
            $remoteCompanies = $this->getCompaniesFromRemote($remoteUnitLink);
        }
        catch (\Exception $exception)
        {
            return $result;
        }

        /** @var CompanyRepository $repository */
        $repository = $this->entityManager->getRepository('App\Entity\Company');
        foreach ($remoteCompanies as $remoteCompany)
        {

            $newCompany = new Company();
            $this->initCompanyFromRemote($newCompany, $remoteCompany['link']);

            $company = $repository->findOneBy(['INN' => $newCompany->getINN()]);
            if (!$company)
            {
                $company = $newCompany;
                $company->setTitle($remoteCompany['name']);
            }

            $administrativeUnit->addCompany($company);

            $this->entityManager->persist($administrativeUnit);
            $this->entityManager->flush($administrativeUnit);

            $result[] = $company;
        }


        return $result;
    }

    private function initCompanyFromRemote(Company $company, string $link)
    {

        $absoluteUrl = $this->getAbsoluteUrl($link);

        $response = $this->httpClient->request('GET', $absoluteUrl);
        if ($response->getStatusCode() !== 200)
        {
            throw new ParseException('Could not get contents from the company URL: "' . $link . '"');
        }

        $crawler = new Crawler($response->getBody()->getContents());
        $crawler->filter('div.uk-content > table > tbody > tr')
            ->each(function(Crawler $node, $index) use ($company) {

                if ($node->children()->count() != 2)
                {
                    return;
                }

                $title = trim($node->children()->eq(0)->text());
                $value = trim($node->children()->eq(1)->text());

                $companyProperty = $this->getCompanyPropertyByRemotePropertyName($title);
                if (empty($companyProperty))
                {
                    return;
                }

                if ($companyProperty === 'buildingNumber')
                {
                    $value = (int)$value;
                }

                if ($companyProperty === 'surface')
                {
                    $value = (float)$value;
                }


                $company->{'set' . ucfirst($companyProperty)}($value);

            });
    }


    private function getCompaniesFromRemote(string $link)
    {
        $result = [];

        $absoluteUrl = $this->getAbsoluteUrl($link);
//        var_dump(['Get companies from ' => $absoluteUrl]);
        $response = $this->httpClient->request('GET', $absoluteUrl);
        if ($response->getStatusCode() !== 200)
        {
            throw new ParseException('Could not get contents from the administrative unit URL: "' . $link . '"');
        }

        $crawler = new Crawler($response->getBody()->getContents());

        $crawler->filter('div.regions-uk-list span a')
            ->each(function (Crawler $node, $index) use (&$result) {

                $result[] = [
                    'name' => trim($node->text()),
                    'link' => $node->attr('href')
                ];

            });


        return $result;
    }

    public function getCompanyPropertyByRemotePropertyName(string $remoteProperty)
    {
        $result = null;

        foreach (self::COMPANY_REMOTE_FIELD_MAP as $remoteLikeProperty => $companyProperty)
        {
            if (mb_strpos($remoteProperty, $remoteLikeProperty) === 0)
            {
                $result = $companyProperty;
                break;
            }
        }

        return $result;
    }

    private function getAbsoluteUrl(string $relativeUrl)
    {
        return self::BASE_REMOTE_URL . $relativeUrl;
    }
}
