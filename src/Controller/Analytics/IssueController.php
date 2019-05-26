<?php

namespace App\Controller\Analytics;

use App\Entity\Region;
use App\Repository\IssueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class IssueController
 * @package App\Controller\Analytics
 */
class IssueController extends AnalyticsCommonController
{
    protected function getAllowedSearchParameters()
    {
        return [
            'year',
            'serviceType',
            'topLeftLatitude',
            'topLeftLongitude',
            'bottomRightLatitude',
            'bottomRightLongitude'
        ];
    }

    /**
     * @param Request $request
     * @param IssueRepository $repository
     * @Route(name="analytics_issue_region_geo_search", path="/issue/region/{id}/geo/search", methods={"GET"})
     * @ParamConverter("region", class="App\Entity\Region")
     */
    public function geoSearch(Region $region, Request $request, IssueRepository $repository)
    {
        // Allowed parameters:
            // year
            // viewbox
            // service type

        $searchCriteria = $this->filterSearchParameters($request->query->all());
        $searchCriteria['region'] = $region;

        $result = $repository->getSearchQuery($searchCriteria)->getResult();
        return $this->getResponse([
            'issues' => $result
        ]);
    }
}