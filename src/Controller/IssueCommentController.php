<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\IssueComment;
use App\Repository\IssueCommentRepository;
use App\Service\EntityManager\IssueCommentManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class IssueCommentController
 * @package App\Controller\Client
 */
class IssueCommentController extends CommonController
{
    /**
     * @param Issue $issue
     * @param IssueCommentRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route(name="issue_comment_issue_comment_list", path="/issue-comment/{id}/list", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     */
    public function issueComments(Issue $issue, IssueCommentRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $searchCriteria = array_merge(
            $request->query->all(),
            [
                'issue' => $issue
            ]
        );

        $query = $repository->getSearchQuery($searchCriteria);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'comments' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ]);
    }

    /**
     * @param Issue $issue
     * @param IssueCommentManager $manager
     * @param Request $request
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(name="issue_comment_create", path="/issue-comment/{id}", methods={"POST"}, requirements={"id"="\d+"})
     * @ParamConverter("issue", class="App\Entity\Issue")
     */
    public function create(Issue $issue, IssueCommentManager $manager, Request $request)
    {
        $comment = $manager->addComment($issue, $request->request->all());

        return $this->getResponse([
            'comment' => $comment
        ], Response::HTTP_CREATED);
    }

    /**
     * @param IssueComment $comment
     * @param IssueCommentManager $manager
     * @param Request $request
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(name="issue_comment_edit", path="/issue-comment/{id}", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @ParamConverter("comment", class="App\Entity\IssueComment")
     */
    public function update(IssueComment $comment, IssueCommentManager $manager, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN_USER'))
        {
            if ($comment->getAuthor() !== $this->getUser())
            {
                throw new AccessDeniedException();
            }
        }

        $comment = $manager->update($comment, $request->request->all());

        return $this->getResponse([
            'comment' => $comment
        ]);
    }

    /**
     * @param IssueComment $comment
     * @param IssueCommentManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @Route(name="issue_comment_delete", path="/issue-comment/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @ParamConverter("comment", class="App\Entity\IssueComment")
     */
    public function delete(IssueComment $comment, IssueCommentManager $manager)
    {
        if (!$this->isGranted('ROLE_ADMIN_USER'))
        {
            if ($comment->getAuthor() !== $this->getUser())
            {
                throw new AccessDeniedException();
            }
        }

        $manager->remove($comment);

        return $this->getResponse();
    }
}