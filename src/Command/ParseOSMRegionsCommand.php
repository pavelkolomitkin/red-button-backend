<?php

namespace App\Command;

use App\Entity\OSMRegion;
use App\Entity\Region;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseOSMRegionsCommand extends Command
{
    protected static $defaultName = 'app:parse-osm-regions';

    CONST REGION_API_BASE_URL = 'https://nominatim.openstreetmap.org/search.php?polygon_geojson=1&viewbox=&format=json&accept-language=ru';

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->httpClient = new Client();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        /** @var RegionRepository $regionRepository */
        $regionRepository = $this->entityManager->getRepository('App\Entity\Region');

        $regions = $regionRepository->findAll();
        foreach ($regions as $region)
        {
            if ($region->getOsmRegion()->getOsmId() !== null)
            {
                continue;
            }

            $output->writeln('Parsing of "' . $region->getTitle() . '"...');
            $this->parseRegion($region);
            gc_collect_cycles();
        }

        $output->writeln('Complete!');
    }

    private function parseRegion(Region $region)
    {
        $data = $this->getOSMRegionInfo($region);

        $osmRegion = new OSMRegion();

        $osmRegion
            ->setPlaceId($data['place_id'])
            ->setOsmType($data['osm_type'])
            ->setOsmId($data['osm_id'])
            ->setBoundingBox($data['boundingbox'])
            ->setLatitude((float)$data['lat'])
            ->setLongitude((float)$data['lon'])
            ->setDisplayName($data['display_name'])
            ->setGeoJson($data['geojson'])
        ;

        $region->setOsmRegion($osmRegion);

        $this->entityManager->persist($region);
        $this->entityManager->flush($region);
    }

    /**
     * Get first OSM result according to the region
     * @param Region $region
     * @return array
     */
    private function getOSMRegionInfo(Region $region)
    {
        $result = [];

        $url = $this->getRegionRequestUrl($region);

        $response = $this->httpClient->request('GET', $url);
        if ($response->getStatusCode() !== 200)
        {
            throw new \Exception('Cannot get remote info of the region: "' . $region->getTitle() . '"');
        }

        $data = json_decode($response->getBody()->getContents(), true);
        if (empty($data) || (empty($data[0])))
        {
            var_dump($data);
            throw new \Exception('Cannot parse info of the region: "' . $region->getTitle() . '"');
        }

        $result = $data[0];


        return $result;
    }

    private function getRegionRequestUrl(Region $region)
    {
        return self::REGION_API_BASE_URL . '&q=' . $region->getTitle();
    }
}