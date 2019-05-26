<?php

namespace App\Controller\Analytics;

use App\Entity\Region;
use App\Service\Analytics\StatisticsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class CompanyController
 * @package App\Controller\Analytics
 */
class CompanyController extends AnalyticsCommonController
{
    /**
     * @Route(name="analytics_company_popular", path="/company/region/{id}/{year}/popular", methods={"GET"})
     * @ParamConverter("region", class="App\Entity\Region")
     */
    public function popular(Region $region, $year, StatisticsService $service)
    {
        $statistics = $service->getRegionPopularCompaniesByYear($region, $year);

        return $this->getResponse([
            'statistics' => $statistics,
            'year' => $year
        ]);
    }
}