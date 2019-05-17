<?php

namespace App\Controller\Company;

use App\Entity\Issue;
use App\Repository\IssueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IssueController
 * @package App\Controller\Company
 */
class IssueController extends CompanyCommonController
{
    protected function getAllowedSearchParameters()
    {
        return [
            'popular',
            'startDate',
            'endDate'
        ];
    }

    /**
     * @param Request $request
     * @param IssueRepository $repository
     *
     * @param PaginatorInterface $paginator
     * @Route(name="company_issue_list", path="/issue/list", methods={"GET"})
     * @return Response
     */
    public function search(Request $request, IssueRepository $repository, PaginatorInterface $paginator)
    {
        $searchCriteria = array_merge(
            $request->query->all(),
            [
                'company' => $this->getCompany()
            ]);

        $query = $repository->getSearchQuery($searchCriteria);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'issues' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ]);
    }

    /**
     * @param Request $request
     * @param IssueRepository $repository
     *
     * @Route(name="company_issue_geo_search", path="/issue/geo/search", methods={"GET"})
     * @return Response
     */
    public function geoSearch(Request $request, IssueRepository $repository)
    {
        $searchCriteria = $request->query->all();
        if (!$repository->hasGeoCriteria($searchCriteria))
        {
            return $this->getResponse([
                'issues' => []
            ]);
        }

        $searchCriteria = array_merge(
            $searchCriteria,
            [
                'company' => $this->getCompany()
            ]
        );


        $issues = $repository->getSearchQuery($searchCriteria)->getResult();

        return $this->getResponse([
            'issues' => $issues
        ]);
    }

    /**
     * @param Issue $issue
     *
     * @Route(name="company_issue_get", path="/issue/{id}", methods={"GET"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     * @return Response
     */
    public function details(Issue $issue)
    {
        if ($issue->getCompany() !== $this->getCompany())
        {
            throw $this->createNotFoundException();
        }

        return $this->getResponse([
            'issue' => $issue
        ]);
    }
}