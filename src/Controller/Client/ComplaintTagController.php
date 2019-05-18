<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Repository\ComplaintTagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ComplaintTagController
 * @package App\Controller\Client
 */
class ComplaintTagController extends ClientCommonController
{
    /**
     * @param Request $request
     * @param ComplaintTagRepository $repository
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(name="client_complaint_tag_list", path="/complaint-tag/list", methods={"GET"})
     */
    public function search(Request $request, ComplaintTagRepository $repository, PaginatorInterface $paginator)
    {
        $query = $repository->getSearchQuery($request->query->all());

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'tags' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ]);
    }
}
