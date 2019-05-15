<?php

namespace App\Controller\Admin;

use App\Entity\Issue;
use App\Repository\IssueRepository;
use App\Service\EntityManager\Admin\IssueManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IssueController
 * @package App\Controller\Admin
 */
class IssueController extends AdminCommonController
{
    /**
     * @param Request $request
     * @param IssueRepository $repository
     * @param PaginatorInterface $paginator
     *
     * @Route(name="admin_issue_list", path="/issue/list", methods={"GET"})
     * @return Response
     */
    public function index(Request $request, IssueRepository $repository, PaginatorInterface $paginator)
    {
        $query = $repository->getSearchQuery($request->query->all());

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
     * @param Issue $issue
     * @return Response
     * @Route(name="admin_issue_details", path="/issue/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     */
    public function details(Issue $issue)
    {
        return $this->getResponse([
            'issue' => $issue,
        ], Response::HTTP_OK, [], [
            'admin_details'
        ]);
    }

    /**
     * @param Issue $issue
     * @param IssueManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     *
     * @Route(name="admin_issue_delete", path="/issue/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     */
    public function delete(Issue $issue, IssueManager $manager)
    {
        $manager->remove($issue);

        return $this->getResponse();
    }
}