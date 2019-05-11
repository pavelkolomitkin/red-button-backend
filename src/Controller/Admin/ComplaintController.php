<?php

namespace App\Controller\Admin;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use App\Service\EntityManager\Admin\ComplaintManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ComplaintController
 * @package App\Controller\Admin
 */
class ComplaintController extends AdminCommonController
{
    /**
     * @param Request $request
     * @param ComplaintRepository $repository
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(name="admin_complaint_list", path="/complaint/list", methods={"GET"})
     */
    public function index(Request $request, ComplaintRepository $repository, PaginatorInterface $paginator)
    {
        $query = $repository->getSearchQuery([]);

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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(name="admin_complaint_details", path="/complaint/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("complaint", class="App\Entity\Complaint")
     */
    public function details(Complaint $complaint)
    {
        return $this->getResponse([
            'complaint' => $complaint
        ]);
    }

    /**
     * @param Complaint $complaint
     * @param ComplaintManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     *
     * @Route(name="admin_complaint_delete", path="/complaint/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     * @ParamConverter("complaint", class="App\Entity\Issue")
     */
    public function delete(Complaint $complaint, ComplaintManager $manager)
    {
        $manager->remove($complaint);

        return $this->getResponse();
    }
}