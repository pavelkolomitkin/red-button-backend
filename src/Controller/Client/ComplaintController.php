<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use App\Service\EntityManager\Client\ComplaintManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ComplaintController
 * @package App\Controller\Client
 */
class ComplaintController extends CommonController
{
    // TODO Add allowed parameters later
    const ALLOWED_SEARCH_PARAMETERS = [
        'centerLatitude',
        'centerLongitude',

        'topLeftLatitude',
        'topLeftLongitude',
        'bottomRightLatitude',
        'bottomRightLongitude',
        'serviceTypeId',
        'tags'

    ];

    private function filterSearchParameters(array $params)
    {
        $result = array_intersect_assoc($params, array_flip(self::ALLOWED_SEARCH_PARAMETERS));

        return $result;
    }

    private function getDefaultSearchDatePeriod()
    {
        return [
            'startDate' => new \DateTime('-1 month'),
            'endDate' => new \DateTime('now')
        ];
    }

    /**
     * @param Request $request
     * @param ComplaintRepository $repository
     * @Route(name="client_complaint_geo_tag_search", path="/complaint/geo-tag/search", methods={"GET"})
     * @return Response
     * @throws \Exception
     */
    public function tagGeoSearch(Request $request, ComplaintRepository $repository)
    {
        $searchCriteria = $this->filterSearchParameters($request->query->all());

        if (!$repository->hasGeoCriteria($searchCriteria))
        {
            return $this->getResponse(
                ['complaints' => []]
            );
        }

        $searchCriteria = array_merge(
            $searchCriteria,
            $this->getDefaultSearchDatePeriod()
        );

        $tags = $repository->getTagSearchQuery($searchCriteria)->getResult();

        return $this->getResponse([
            'tags' => $tags
        ]);
    }

    /**
     * @param Request $request
     * @param ComplaintRepository $repository
     * @Route(name="client_complaint_geo_search", path="/complaint/geo/search", methods={"GET"})
     * @return Response
     * @throws \Exception
     */
    public function geoSearch(Request $request, ComplaintRepository $repository)
    {
        $searchCriteria = $this->filterSearchParameters($request->query->all());
        if (!$repository->hasGeoCriteria($searchCriteria))
        {
            return $this->getResponse(
                ['complaints' => []]
            );
        }

        $searchCriteria = array_merge(
            $searchCriteria,
            $this->getDefaultSearchDatePeriod()
        );

        $complaints = $repository->getSearchQuery($searchCriteria)->getResult();

        return $this->getResponse(
            ['complaints' => $complaints],
            Response::HTTP_OK, [], [
                'client_complaint_list'
            ]
        );
    }

    /**
     * @param Request $request
     * @param ComplaintRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route(name="client_complaint_my_list", path="/complaint/my/list", methods={"GET"})
     */
    public function getUserComplaints(Request $request, ComplaintRepository $repository, PaginatorInterface $paginator)
    {
        $searchCriteria = [
            'client' => $this->getUser()
        ];

        $query = $repository->getSearchQuery($searchCriteria);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'complaints' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ],
            Response::HTTP_OK, [], [
                'client_complaint_list',
                'client_complaint_details',
                'client_complaint_details_tags'
            ]);
    }

    /**
     * @param Complaint $complaint
     * @Route(name="client_complaint_details", path="/complaint/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("complaint", class="App\Entity\Complaint")
     * @return Response
     */
    public function details(Complaint $complaint)
    {
        return $this->getResponse([
            'complaint' => $complaint
        ],
            Response::HTTP_OK, [], [

                'client_complaint_details',
                'client_complaint_details_tags'
            ]);
    }

    /**
     * @param Request $request
     * @param ComplaintManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(name="client_complaint_create", path="/complaint", methods={"POST"})
     */
    public function create(Request $request, ComplaintManager $manager)
    {
        $complaint = $manager->create($request->request->all());

        return $this->getResponse([
            'complaint' => $complaint
        ], Response::HTTP_CREATED, [], [

            'client_complaint_details',
            'client_complaint_details_tags'
        ]);
    }

    /**
     * @param Complaint $complaint
     * @param ComplaintManager $manager
     * @param Request $request
     * @Route(name="client_complaint_update", path="/complaint/{id}", methods={"PUT"}, requirements={"id"="\d+"})
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @ParamConverter("complaint", class="App\Entity\Complaint")
     */
    public function update(Complaint $complaint, ComplaintManager $manager, Request $request)
    {
        if ($complaint->getClient() !== $this->getUser())
        {
            throw new AccessDeniedException();
        }

        $complaint = $manager->update($complaint, $request->request->all());

        return $this->getResponse([
            'complaint' => $complaint
        ], Response::HTTP_OK, [], [
            'client_complaint_details',
            'client_complaint_details_tags'
        ]);
    }

    /**
     * @param Complaint $complaint
     * @param ComplaintManager $manager
     * @Route(name="client_complaint_delete", path="/complaint/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     * @ParamConverter("complaint", class="App\Entity\Complaint")
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function delete(Complaint $complaint, ComplaintManager $manager)
    {
        if ($complaint->getClient() !== $this->getUser())
        {
            throw new AccessDeniedException();
        }

        $manager->remove($complaint);

        return $this->getResponse();
    }
}
