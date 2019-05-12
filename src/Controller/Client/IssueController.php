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
    // TODO Add allowed parameters later
    const ALLOWED_SEARCH_PARAMETERS = [

    ];

    private function filterSearchParameters(array $params)
    {
        $result = array_intersect_assoc($params, array_flip(self::ALLOWED_SEARCH_PARAMETERS));

        return $result;
    }

    /**
     * @param Request $request
     * @param IssueRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route(name="client_issue_my_list", path="/issue/my/list", methods={"GET"})
     */
    public function getUserIssues(Request $request, IssueRepository $repository, PaginatorInterface $paginator)
    {
        $searchCriteria = $this->filterSearchParameters($request->query->all());

        $searchCriteria = array_merge(
            $searchCriteria,
            [
                'client' => $this->getUser()
            ]
        );

        // TODO Add filter of allowed parameters
        $query = $repository->getSearchQuery($searchCriteria);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'issues' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ], Response::HTTP_OK, [], [
            'client_issue_list'
        ]);
    }

    /**
     * @param Issue $issue
     * @param IssueManager $manager
     * @return Response
     * @Route(name="client_issue_details", path="/issue/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     */
    public function details(Issue $issue, IssueManager $manager)
    {
        $hasLike = $manager->hasUserLike($issue, $this->getUser());

        return $this->getResponse([
            'issue' => $issue,
            'hasLike' => $hasLike
        ], Response::HTTP_OK, [], [
            'client_issue_details', 'client_complaint_details'
        ]);
    }

    /**
     * @param Request $request
     * @param IssueManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(name="client_issue_create", path="/issue", methods={"POST"})
     */
    public function create(Request $request, IssueManager $manager)
    {
        $issue = $manager->create($request->request->all());

        return $this->getResponse([
            'issue' => $issue
        ], Response::HTTP_CREATED, [], [
            'client_issue_details', 'client_complaint_details'
        ]);
    }

    /**
     * @param Request $request
     * @param Issue $issue
     * @param IssueManager $manager
     * @Route(name="client_issue_edit", path="/issue/{id}", methods={"PUT"}, requirements={"id"="\d+"})
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
            ], Response::HTTP_OK, [], [
            'client_issue_details', 'client_complaint_details'
        ]);
    }

    /**
     * @param Issue $issue
     * @param IssueManager $manager
     *
     * @Route(name="client_issue_delete", path="/issue/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
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

    /**
     * @param Issue $issue
     * @param IssueManager $manager
     * @return Response
     * @throws \Exception
     * @ParamConverter("issue", class="App\Entity\Issue")
     * @Route(name="client_issue_like_add", path="/issue/{id}/add-like", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function addLike(Issue $issue, IssueManager $manager)
    {
        $manager->addLike($issue, $this->getUser());

        return $this->getResponse([
            'issue' => $issue,
            'hasLike' => true
        ], Response::HTTP_OK, [], [
            'client_issue_details', 'client_complaint_details'
        ]);
    }

    /**
     * @param Issue $issue
     * @param IssueManager $manager
     * @return Response
     * @throws \Exception
     *
     * @ParamConverter("issue", class="App\Entity\Issue")
     *
     * @Route(name="client_issue_like_remove", path="/issue/{id}/remove-like", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function removeLike(Issue $issue, IssueManager $manager)
    {
        $manager->removeLike($issue, $this->getUser());

        return $this->getResponse([
            'issue' => $issue,
            'hasLike' => false
        ], Response::HTTP_OK, [], [
            'client_issue_details', 'client_complaint_details'
        ]);
    }
}