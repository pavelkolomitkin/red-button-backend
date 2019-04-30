<?php


namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Entity\Issue;
use App\Repository\IssueRepository;
use App\Service\EntityManager\Client\IssueManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IssueController
 * @package App\Controller\Client
 */
class IssueController extends CommonController
{
    /**
     * @param Request $request
     * @param IssueRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route(name="issue_my_list", path="/issue/my/list", methods={"GET"})
     */
    public function getUserIssues(Request $request, IssueRepository $repository, PaginatorInterface $paginator)
    {
        $searchCriteria = array_merge(
            $request->query->all(),
            [
                'client' => $this->getUser()
            ]
        );

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
     * @param Issue $issue
     * @Route(name="issue_details", path="/issue/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     * @return Response
     */
    public function details(Issue $issue)
    {
        return $this->getResponse([
            'issue' => $issue
        ]);
    }

    /**
     * @param Request $request
     * @param IssueManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(name="issue_create", path="/issue", methods={"POST"})
     */
    public function create(Request $request, IssueManager $manager)
    {
        $issue = $manager->create($request->request->all());

        return $this->getResponse([
            'issue' => $issue
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param Issue $issue
     * @param IssueManager $manager
     * @Route(name="issue_edit", path="/issue/{id}", methods={"PUT"}, requirements={"id"="\d+"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function update(Issue $issue, IssueManager $manager, Request $request)
    {
        if ($issue->getClient() !== $this->getUser())
        {
            throw new AccessDeniedException();
        }

        $issue = $manager->update($issue, $request->request->all());
        return $this->getResponse([
            'issue' => $issue
        ]);
    }

    /**
     * @param Issue $issue
     * @param IssueManager $manager
     *
     * @Route(name="issue_delete", path="/issue/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function delete(Issue $issue, IssueManager $manager)
    {
        if ($issue->getClient() !== $this->getUser())
        {
            throw new AccessDeniedException();
        }

        $manager->remove($issue);
        return $this->getResponse();
    }
}