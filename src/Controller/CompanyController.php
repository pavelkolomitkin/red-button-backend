<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompanyController
 * @package App\Controller\Client
 */
class CompanyController extends CommonController
{
    /**
     * @param Request $request
     * @param CompanyRepository $repository
     * @param PaginatorInterface $paginator
     * @Route(name="company_search", path="/company/search", methods={"GET"})
     * @return Response
     */
    public function search(Request $request, CompanyRepository $repository, PaginatorInterface $paginator)
    {
        $query = $repository->getSearchQuery($request->query->all());

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->getResponse([
            'companies' => $pagination->getItems(),
            'total' => $pagination->getTotalItemCount()
        ]);
    }
}
