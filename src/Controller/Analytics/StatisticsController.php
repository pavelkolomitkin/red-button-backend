<?php

namespace App\Controller\Analytics;

use App\Entity\FederalDistrict;
use App\Entity\Region;
use App\Service\Analytics\StatisticsService;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class StatisticsController
 * @package App\Controller\Analytics
 */
class StatisticsController extends AnalyticsCommonController
{
    /**
     * @param $year
     * @param StatisticsService $service
     * @Route(
     *     name="analytics_statistics_country_numbers",
     *     path="/statistics/country-numbers/{year}",
     *     methods={"GET"},
     *     requirements={"year"="\d{4,4}"}
     * )
     * @return Response
     */
    public function getCountryIssueNumbers($year, StatisticsService $service)
    {
        $commonStatistics = $service->getCountryServiceTypeIssueNumberByYear($year);
        $byFederalDistrictStatistics = $service->getIssueNumbersOfFederalDistrictsByYear($year);

        return $this->getResponse([
            'statistics' =>
                [
                    'common' =>  $commonStatistics,
                    'byFederalDistricts' => $byFederalDistrictStatistics
                ],
            'year' => $year
        ]);
    }

    /**
     * @param $year
     * @param StatisticsService $service
     * @return Response
     *
     * @Route(
     *     name="analytics_statistics_country_numbers_dynamic",
     *     path="/statistics/country-numbers/dynamic/{year}",
     *     methods={"GET"},
     *     requirements={"year"="\d{4,4}"}
     * )
     */
    public function getCountryIssueNumberDynamics($year, StatisticsService $service)
    {
        $statistics = $service->getIssueNumberDynamicByYear($year);

        return $this->getResponse([
            'statistics' => $statistics,
            'year' => $year
        ]);
    }

    /**
     * @param $year
     * @param StatisticsService $service
     * @Route(
     *     name="analytics_statistics_federal_district_numbers",
     *     path="/statistics/federal-district-numbers/{id}/{year}",
     *     methods={"GET"},
     *     requirements={"id"="\d+", "year"="\d{4,4}"}
     * )
     * @ParamConverter("district", class="App\Entity\FederalDistrict")
     */
    public function getFederalDistrictNumbers(FederalDistrict $district, $year, StatisticsService $service)
    {
        $commonStatistics = $service->getFederalDistrictIssueNumberByYear($district, $year);
        $byRegionStatistics = $service->getIssueNumbersOfRegionsByYear($district, $year);

        return $this->getResponse([
            'statistics' =>
                [
                    'common' =>  $commonStatistics,
                    'byRegions' => $byRegionStatistics
                ],
            'year' => $year
        ]);
    }

    /**
     * @param FederalDistrict $district
     * @param $year
     * @param StatisticsService $service
     * @return Response
     *
     * @Route(
     *     name="analytics_statistics_federal_district_numbers_dynamic",
     *     path="/statistics/federal-district-numbers/dynamic/{id}/{year}",
     *     methods={"GET"},
     *     requirements={"id"="\d+", "year"="\d{4,4}"}
     * )
     * @ParamConverter("district", class="App\Entity\FederalDistrict")
     */
    public function getFederalDistrictIssueNumberDynamics(FederalDistrict $district, $year, StatisticsService $service)
    {
        $statistics = $service->getFederalDistrictIssueNumberDynamicYear($district, $year);

        return $this->getResponse([
            'statistics' => $statistics,
            'year' => $year
        ]);
    }

    /**
     * @param Region $region
     * @param $year
     * @param StatisticsService $service
     * @Route(
     *     name="analytics_statistics_region_numbers",
     *     path="/statistics/region/{id}/{year}",
     *     methods={"GET"},
     *     requirements={"id"="\d+", "year"="\d{4,4}"}
     * )
     * @ParamConverter("region", class="App\Entity\Region")
     */
    public function getRegionIssueNumbers(Region $region, $year, StatisticsService $service)
    {
        $statistics = $service->getRegionIssueNumberByYear($region, $year);

        return $this->getResponse([
            'statistics' => [
                'common' => $statistics
            ],
            'year' => $year
        ]);
    }


    /**
     * @param Region $region
     * @param $year
     * @param StatisticsService $service
     * @return Response
     *
     * @Route(
     *     name="analytics_statistics_region_numbers_dynamic",
     *     path="/statistics/region-numbers/dynamic/{id}/{year}",
     *     methods={"GET"},
     *     requirements={"id"="\d+", "year"="\d{4,4}"}
     * )
     * @ParamConverter("region", class="App\Entity\Region")
     */
    public function getRegionIssueNumbersDynamic(Region $region, $year, StatisticsService $service)
    {
        $statistics = $service->getRegionIssueNumberDynamicByYear($region, $year);

        return $this->getResponse([
            'statistics' => $statistics,
            'year' => $year
        ]);
    }
}