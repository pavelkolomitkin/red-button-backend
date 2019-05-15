<?php


namespace App\Controller\Admin;

use App\Repository\IssueCommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IssueCommentController extends AdminCommonController
{
    /**
     * @param Request $request
     * @param IssueCommentRepository $repository
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(name="admin_issue_comment_list", path="/issue-comment/list", methods={"GET"})
     */
    public function getList(Request $request, IssueCommentRepository $repository, PaginatorInterface $paginator)
    {
        $searchCriteria = $request->query->all();
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
}