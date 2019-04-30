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

    /**
     * @param Request $request
     * @param ComplaintRepository $repository
     * @Route(name="complaint_search", path="/complaint/search", methods={"GET"})
     * @return Response
     * @throws \Exception
     */
    public function search(Request $request, ComplaintRepository $repository)
    {
        $criteria = array_merge(
            $request->query->all(),
            [
                'timeStart' => new \DateTime('-1 month'),
                'timeEnd' => new \DateTime('now')
            ]
        );

        $complaints = $repository->getSearchQuery($criteria)->getResult();

        return $this->getResponse(
            ['complaints' => $complaints]
        );
    }

    /**
     * @param Request $request
     * @param ComplaintRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route(name="complaint_my_list", path="/complaint/my/list", methods={"GET"})
     */
    public function getUserComplaints(Request $request, ComplaintRepository $repository, PaginatorInterface $paginator)
    {
        $searchCriteria = [
            'owner' => $this->getUser()
        ];

        $query = $repository->getSearchQuery($searchCriteria);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'complaints' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ]);
    }

    /**
     * @param Complaint $complaint
     * @Route(name="complaint_details", path="/complaint/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("complaint", class="App\Entity\Complaint")
     * @return Response
     */
    public function details(Complaint $complaint)
    {
        return $this->getResponse([
            'complaint' => $complaint
        ]);
    }

    /**
     * @param Request $request
     * @param ComplaintManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(name="complaint_create", path="/complaint", methods={"POST"})
     */
    public function create(Request $request, ComplaintManager $manager)
    {
        $complaint = $manager->create($request->request->all());

        return $this->getResponse([
            'complaint' => $complaint
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Complaint $complaint
     * @param ComplaintManager $manager
     * @param Request $request
     * @Route(name="complaint_update", path="/complaint/{id}", methods={"PUT"}, requirements={"id"="\d+"})
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
        ]);
    }

    /**
     * @param Complaint $complaint
     * @param ComplaintManager $manager
     * @Route(name="complaint_delete", path="/complaint/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
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
