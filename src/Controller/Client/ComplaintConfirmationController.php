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
class ComplaintConfirmationController extends CommonController
{
    /**
     * @param Request $request
     *
     * @param ComplaintConfirmationRepository $repository
     * @param PaginatorInterface $paginator
     * @Route(
     *     name="complaint_confirmation_request_list",
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
     * @param Issue $issue
     * @param Complaint $complaint
     * @param ComplaintConfirmationManager $manager
     * @return Response
     *
     * @Route(
     *     name="complaint_confirmation_change_status",
     *     path="/complaint-confirmation/create/{issueId}/{complaintId}",
     *     methods={"POST"},
     *     requirements={"issueId"="\d+", "complaintId"="\d+"}
     * )
     * @ParamConverter("issue", class="App\Entity\Issue", options={"id" = "issueId"})
     * @ParamConverter("complaint", class="App\Entity\Complaint", options={"id" = "issueId"})
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function create(Issue $issue, Complaint $complaint, ComplaintConfirmationManager $manager)
    {
        $confirmation = $manager->create([
            'issue' => $issue,
            'complaint' => $complaint
        ]);

        return $this->getResponse([
            'confirmation' => $confirmation
        ], Response::HTTP_CREATED);
    }

    /**
     * @param ComplaintConfirmation $confirmation
     * @param ComplaintConfirmationManager $manager
     * @param Request $request
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(
     *     name="complaint_confirmation_change_status",
     *     path="/complaint-confirmation/{id}",
     *     methods={"PUT"},
     *     requirements={"id"="\d+"}
     * )
     * @ParamConverter("confirmation", class="App\Entity\ComplaintConfirmation")
     */
    public function changeStatus(ComplaintConfirmation $confirmation, ComplaintConfirmationManager $manager, Request $request)
    {
        $confirmation = $manager->update($confirmation, $request->request->all());

        return $this->getResponse([
            'confirmation' => $confirmation
        ]);
    }

    /**
     * @param ComplaintConfirmation $confirmation
     * @param ComplaintConfirmationManager $manager
     *
     * @Route(
     *     name="complaint_confirmation_remove",
     *     path="/complaint-confirmation/{id}",
     *     methods={"DELETE"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @ParamConverter("confirmation", class="App\Entity\ComplaintConfirmation")
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function delete(ComplaintConfirmation $confirmation, ComplaintConfirmationManager $manager)
    {
        if ($confirmation->getIssue()->getClient() !== $this->getUser())
        {
            throw new AccessDeniedException();
        }

        $manager->remove($confirmation);

        return $this->getResponse();
    }
}