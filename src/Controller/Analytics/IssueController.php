<?php

namespace App\Controller\Analytics;

use App\Entity\Company;
use App\Entity\Issue;
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
     * @param Region $region
     * @param Request $request
     * @param IssueRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(name="analytics_issue_region_geo_search", path="/issue/region/{id}/geo/search", methods={"GET"})
     * @ParamConverter("region", class="App\Entity\Region")
     */
    public function regionGeoSearch(Region $region, Request $request, IssueRepository $repository)
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

    /**
     * @param Company $company
     * @param Request $request
     * @param IssueRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     name="analytics_issue_company_geo_search",
     *     path="/issue/company/{id}/geo/search",
     *     methods={"GET"},
     *     requirements={"id"="\d+"}
     * )
     * @ParamConverter("company", class="App\Entity\Company")
     */
    public function companyGeoSearch(Company $company, Request $request, IssueRepository $repository)
    {
        $searchCriteria = $this->filterSearchParameters($request->query->all());
        $searchCriteria['company'] = $company;

        $result = $repository->getSearchQuery($searchCriteria)->getResult();
        return $this->getResponse([
            'issues' => $result
        ]);
    }

    /**
     * @param Issue $issue
     * @Route(
     *     name="analytics_issue_get_details",
     *     path="/issue/{id}",
     *     methods={"GET"},
     *     requirements={"id"="\d+"}
     * )
     * @ParamConverter("issue", class="App\Entity\Issue")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details(Issue $issue)
    {
        return $this->getResponse([
            'issue' => $issue
        ]);
    }
}