<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Entity\Complaint;
use App\Entity\ComplaintConfirmation;
use App\Entity\Issue;
use App\Repository\ComplaintConfirmationRepository;
use App\Service\EntityManager\Client\ComplaintConfirmationManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class ComplaintConfirmationController
 * @package App\Controller\Client
 */
class ComplaintConfirmationController extends ClientCommonController
{
    /**
     * @param Request $request
     *
     * @param ComplaintConfirmationRepository $repository
     * @param PaginatorInterface $paginator
     * @Route(
     *     name="client_complaint_confirmation_request_list",
     *     path="/complaint-confirmation/request/list",
     *     methods={"GET"}
     * )
     * @return Response
     */
    public function requestList(Request $request, ComplaintConfirmationRepository $repository, PaginatorInterface $paginator)
    {
        $searchCriteria = array_merge(
                $request->query->all(), [
                'addressee' => $this->getUser()
            ]);

        $query = $repository->getSearchQuery($searchCriteria);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'confirmations' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ]);
    }

    /**
     * @param ComplaintConfirmation $confirmation
     * @param ComplaintConfirmationManager $manager
     * @param Request $request
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(
     *     name="client_complaint_confirmation_change_status",
     *     path="/complaint-confirmation/{id}",
     *     methods={"PUT"},
     *     requirements={"id"="\d+"}
     * )
     * @ParamConverter("confirmation", class="App\Entity\ComplaintConfirmation")
     */
    public function changeStatus(ComplaintConfirmation $confirmation, ComplaintConfirmationManager $manager, Request $request)
    {
        if ($confirmation->getComplaint()->getClient() !== $this->getUser())
        {
            throw new AccessDeniedException();
        }

        $confirmation = $manager->changeStatus($confirmation, $request->request->get('status'));

        return $this->getResponse([
            'confirmation' => $confirmation,
            'issue' => $confirmation->getIssue()
        ], Response::HTTP_OK, [], [
            'client_complaint_details',
            'client_issue_details'
        ]);
    }
}