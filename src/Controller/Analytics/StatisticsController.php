<?php

namespace App\Controller\Analytics;

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
}